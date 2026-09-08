import fs from 'node:fs/promises';

const required=['GOOGLE_CLIENT_ID','GOOGLE_CLIENT_SECRET','GOOGLE_REFRESH_TOKEN','GBP_ACCOUNT_ID','GBP_LOCATION_ID'];
for(const k of required){if(!process.env[k]){console.error(`Variable manquante: ${k}`);process.exit(1)}}

const OUT='data/reviews.json';
const minRating=Number(process.env.REVIEWS_MIN_RATING||4);
const clientId=process.env.GOOGLE_CLIENT_ID;
const clientSecret=process.env.GOOGLE_CLIENT_SECRET;
const refreshToken=process.env.GOOGLE_REFRESH_TOKEN;
const accountId=process.env.GBP_ACCOUNT_ID;
const locationId=process.env.GBP_LOCATION_ID;

async function getAccessToken(){
  const body=new URLSearchParams({client_id:clientId,client_secret:clientSecret,refresh_token:refreshToken,grant_type:'refresh_token'});
  const r=await fetch('https://oauth2.googleapis.com/token',{method:'POST',headers:{'content-type':'application/x-www-form-urlencoded'},body});
  if(!r.ok) throw new Error(`OAuth ${r.status}: ${await r.text()}`);
  return (await r.json()).access_token;
}

async function fetchAllReviews(token){
  let pageToken='';
  const reviews=[];
  do{
    const u=new URL(`https://mybusiness.googleapis.com/v4/accounts/${encodeURIComponent(accountId)}/locations/${encodeURIComponent(locationId)}/reviews`);
    u.searchParams.set('pageSize','50');
    if(pageToken) u.searchParams.set('pageToken',pageToken);
    const r=await fetch(u,{headers:{authorization:`Bearer ${token}`}});
    if(!r.ok) throw new Error(`Reviews API ${r.status}: ${await r.text()}`);
    const j=await r.json();
    reviews.push(...(j.reviews||[]));
    pageToken=j.nextPageToken||'';
  }while(pageToken);
  return reviews;
}

function ratingNumber(v){
  if(typeof v==='number') return v;
  const map={ONE:1,TWO:2,THREE:3,FOUR:4,FIVE:5};
  return map[String(v||'').toUpperCase()]||0;
}

function normalize(r){
  return {
    reviewId:r.reviewId||'',
    author:r.reviewer?.displayName||'Client Google',
    profilePhoto:r.reviewer?.profilePhotoUrl||'',
    rating:ratingNumber(r.starRating),
    comment:r.comment||'',
    createTime:r.createTime||'',
    updateTime:r.updateTime||'',
    reply:r.reviewReply?.comment||''
  };
}

const token=await getAccessToken();
const raw=await fetchAllReviews(token);
const all=raw.map(normalize).sort((a,b)=>new Date(b.updateTime||b.createTime)-new Date(a.updateTime||a.createTime));
const visible=all.filter(r=>r.rating>=minRating && r.comment.trim());
const avg=all.length ? all.reduce((s,r)=>s+r.rating,0)/all.length : 0;
const data={
  generatedAt:new Date().toISOString(),
  source:'Google Business Profile API',
  summary:{rating:avg.toFixed(1).replace('.',','),count:all.length},
  minRating,
  reviewUrl:process.env.GOOGLE_REVIEWS_PUBLIC_URL||'https://www.google.com/search?q=REVO+TRAVAUX+Martinique+avis',
  reviews:visible
};
await fs.mkdir('data',{recursive:true});
await fs.writeFile(OUT,JSON.stringify(data,null,2)+'\n','utf8');
console.log(`OK: ${all.length} avis récupérés, ${visible.length} affichés.`);