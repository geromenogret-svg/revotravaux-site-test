(function(){
  const ROOT_SELECTOR='[data-revo-reviews]';
  const DATA_URL='data/reviews.json';
  const AUTOPLAY_MS=6000;

  function stars(n){return '★★★★★'.slice(0,Math.max(0,Math.min(5,n||0)));}
  function initials(name){return (name||'?').split(/\s+/).filter(Boolean).slice(0,2).map(x=>x[0].toUpperCase()).join('');}
  function formatDate(s){if(!s)return ''; const d=new Date(s); if(Number.isNaN(d.getTime())) return s; return new Intl.DateTimeFormat('fr-FR',{month:'long',year:'numeric'}).format(d);}
  function escapeHtml(s){return String(s??'').replace(/[&<>'"]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[m]));}

  async function init(root){
    root.innerHTML='<div class="rr-wrap"><div class="rr-status">Chargement des avis Google…</div></div>';
    let data;
    try{
      const res=await fetch(root.dataset.src||DATA_URL,{cache:'no-store'});
      if(!res.ok) throw new Error('HTTP '+res.status);
      data=await res.json();
    }catch(err){
      root.innerHTML='<div class="rr-wrap"><div class="rr-status">Les avis sont momentanément indisponibles.</div></div>';
      return;
    }

    const reviews=(data.reviews||[]).filter(r=>!data.minRating || Number(r.rating)>=Number(data.minRating));
    const summary=data.summary||{};
    const reviewUrl=data.reviewUrl||'#';

    root.innerHTML=`<div class="rr-wrap">
      <div class="rr-head">
        <span class="rr-kicker">Avis clients</span>
        <h2>Ils nous font confiance</h2>
        <div class="rr-summary"><span class="rr-rating">${escapeHtml(summary.rating||'')}</span><span class="rr-stars">★★★★★</span> ${summary.count?`· ${escapeHtml(summary.count)} avis Google`:''}</div>
      </div>
      <div class="rr-shell">
        <div class="rr-viewport"><div class="rr-track"></div></div>
        <div class="rr-controls"><button class="rr-btn rr-prev" aria-label="Avis précédent">‹</button><button class="rr-btn rr-next" aria-label="Avis suivant">›</button></div>
        <div class="rr-dots"></div>
      </div>
      <div class="rr-cta"><a href="${escapeHtml(reviewUrl)}" target="_blank" rel="noopener">Voir tous nos avis Google →</a></div>
    </div>`;

    const track=root.querySelector('.rr-track');
    reviews.forEach(r=>{
      const card=document.createElement('article'); card.className='rr-card';
      const full=String(r.comment||''); const short=full.length>260?full.slice(0,257).trim()+'…':full;
      card.innerHTML=`<div class="rr-card-top"><div class="rr-author">${r.profilePhoto?`<img class="rr-avatar" src="${escapeHtml(r.profilePhoto)}" alt="">`:`<div class="rr-avatar rr-avatar-fallback">${escapeHtml(initials(r.author))}</div>`}<div><div class="rr-name">${escapeHtml(r.author||'Client Google')}</div><div class="rr-date">${escapeHtml(formatDate(r.createTime||r.updateTime))}</div></div></div><div class="rr-google">Google</div></div><div class="rr-card-stars">${stars(Number(r.rating)||5)}</div><div class="rr-text"></div>${full.length>260?'<button class="rr-more">Lire la suite</button>':''}`;
      card.querySelector('.rr-text').textContent=short;
      const more=card.querySelector('.rr-more');
      if(more){let open=false;more.addEventListener('click',()=>{open=!open;card.querySelector('.rr-text').textContent=open?full:short;more.textContent=open?'Réduire':'Lire la suite';});}
      track.appendChild(card);
    });

    const dots=root.querySelector('.rr-dots');
    let index=0, timer=null, perView=3;
    const calcPerView=()=>window.innerWidth<=620?1:window.innerWidth<=900?2:3;
    const maxIndex=()=>Math.max(0,reviews.length-perView);
    function render(){perView=calcPerView(); index=Math.min(index,maxIndex()); const card=track.querySelector('.rr-card'); if(!card)return; const gap=18; const step=card.getBoundingClientRect().width+gap; track.style.transform=`translateX(${-index*step}px)`; dots.innerHTML=''; for(let i=0;i<=maxIndex();i++){const b=document.createElement('button'); b.className='rr-dot'+(i===index?' active':''); b.setAttribute('aria-label','Aller à l\'avis '+(i+1)); b.addEventListener('click',()=>{index=i;render();restart();}); dots.appendChild(b);} }
    function next(){index=index>=maxIndex()?0:index+1;render();}
    function prev(){index=index<=0?maxIndex():index-1;render();}
    function restart(){clearInterval(timer); if(reviews.length>perView) timer=setInterval(next,AUTOPLAY_MS);}
    root.querySelector('.rr-next').addEventListener('click',()=>{next();restart();});
    root.querySelector('.rr-prev').addEventListener('click',()=>{prev();restart();});
    root.addEventListener('mouseenter',()=>clearInterval(timer)); root.addEventListener('mouseleave',restart);
    let sx=null; root.addEventListener('touchstart',e=>sx=e.touches[0].clientX,{passive:true}); root.addEventListener('touchend',e=>{if(sx===null)return; const dx=e.changedTouches[0].clientX-sx; if(Math.abs(dx)>40){dx<0?next():prev();restart();} sx=null;},{passive:true});
    window.addEventListener('resize',render); render(); restart();
  }

  document.addEventListener('DOMContentLoaded',()=>document.querySelectorAll(ROOT_SELECTOR).forEach(init));
})();