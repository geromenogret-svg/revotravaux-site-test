<?php
/* ═══════════════════════════════════════════════════════════════
   REVO TRAVAUX — realisations.php
   
   Scanne automatiquement realisations/*.html
   Chaque réalisation doit avoir ces meta dans son <head> :
     <meta name="project-date" content="2025-04-15">
     <meta name="project-cat" content="renovation-complete">
     <meta name="project-summary" content="Description courte...">
     <meta name="project-image" content="https://...image.jpg">
     <meta name="project-location" content="Fort-de-France">
     <meta name="project-budget" content="48 000 €">
     <meta name="project-quote" content="Témoignage client ici...">
   
   POUR AJOUTER UNE RÉALISATION : 
   Copier realisations/_TEMPLATE.html → realisations/mon-projet.html
   Modifier les meta + le contenu. C'est tout.
   ═══════════════════════════════════════════════════════════════ */

/* ── Chantiers réels publiés depuis REVO Pilotage (4 photos au plus chacun, sans nom de client ni position) ──
   Lus côté serveur, gardés 10 minutes en cache. Si l'app ne répond pas, la page s'affiche sans eux. */
function revo_site_projects() {
    $cache = sys_get_temp_dir() . '/revo-site-realisations.json';
    if (is_file($cache) && time() - filemtime($cache) < 600) {
        $d = json_decode((string) @file_get_contents($cache), true);
        if (is_array($d)) return $d;
    }
    $url = (getenv('REVO_PILOTAGE_URL') ?: 'https://pilotage.revotravaux.fr') . '/api/public/site';
    $raw = false;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 4, CURLOPT_CONNECTTIMEOUT => 3]);
        $raw = curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) $raw = false;
        curl_close($ch);
    } else {
        $raw = @file_get_contents($url, false, stream_context_create(['http' => ['timeout' => 4]]));
    }
    $d = $raw ? json_decode($raw, true) : null;
    if (is_array($d) && isset($d['projects'])) {
        @file_put_contents($cache, $raw);
        return $d;
    }
    // App injoignable : on garde l'ancienne liste si elle existe
    $old = is_file($cache) ? json_decode((string) @file_get_contents($cache), true) : null;
    return is_array($old) ? $old : ['projects' => [], 'vitrine' => null];
}
$live = revo_site_projects();
$live_projects = $live['projects'] ?? [];
$vitrine_url = $live['vitrine'] ?? null;

$projects = [];
$dir = __DIR__ . '/realisations/';
foreach (glob($dir . '*.html') as $filepath) {
    $filename = basename($filepath);
    if ($filename === '_TEMPLATE.html') continue;
    $content = file_get_contents($filepath);
    
    $title = '';
    if (preg_match('/<title>(.*?)<\/title>/s', $content, $m))
        $title = trim(explode('—', strip_tags($m[1]))[0]);
    
    $date = '2025-01-01';
    if (preg_match('/<meta\s+name="project-date"\s+content="([^"]*)"/', $content, $m)) $date = $m[1];
    $cat = 'renovation-complete';
    if (preg_match('/<meta\s+name="project-cat"\s+content="([^"]*)"/', $content, $m)) $cat = $m[1];
    $summary = '';
    if (preg_match('/<meta\s+name="project-summary"\s+content="([^"]*)"/', $content, $m)) $summary = $m[1];
    $image = '';
    if (preg_match('/<meta\s+name="project-image"\s+content="([^"]*)"/', $content, $m)) $image = $m[1];
    $location = '';
    if (preg_match('/<meta\s+name="project-location"\s+content="([^"]*)"/', $content, $m)) $location = $m[1];
    $budget = '';
    if (preg_match('/<meta\s+name="project-budget"\s+content="([^"]*)"/', $content, $m)) $budget = $m[1];
    $quote = '';
    if (preg_match('/<meta\s+name="project-quote"\s+content="([^"]*)"/', $content, $m)) $quote = $m[1];
    $mode = '';
    if (preg_match('/<meta\s+name="project-mode"\s+content="([^"]*)"/', $content, $m)) $mode = $m[1];
    
    $projects[] = compact('filename','title','date','cat','summary','image','location','budget','quote','mode') + ['ts'=>strtotime($date)?:0];
}
usort($projects, function($a,$b){return $b['ts']-$a['ts'];});

$cats = [
    'renovation-complete' => '🏠 Rénovation complète',
    'cuisine' => '🍳 Cuisine',
    'salle-de-bain' => '🛁 Salle de bain',
    'renovation-energetique' => '🌿 Énergie',
    'electricite' => '⚡ Électricité',
    'plomberie' => '🚰 Plomberie',
    'exterieur' => '🌴 Extérieur',
];
$cat_short = [
    'renovation-complete' => 'Rénovation complète',
    'cuisine' => 'Cuisine',
    'salle-de-bain' => 'Salle de bain',
    'renovation-energetique' => 'Énergie',
    'electricite' => 'Électricité',
    'plomberie' => 'Plomberie',
    'exterieur' => 'Extérieur',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5RPQHRD5');</script>
<!-- End Google Tag Manager -->
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-VQVNMTBHNY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-VQVNMTBHNY');
</script>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Nos Réalisations de Rénovation en Martinique — REVO TRAVAUX</title>
<meta name="description" content="Découvrez les projets de rénovation réalisés par REVO TRAVAUX en Martinique : cuisines, salles de bain, rénovation complète, isolation. Photos et avis clients.">
<link rel="canonical" href="https://revotravaux.fr/realisations.php">
<meta property="og:title" content="Nos Réalisations — REVO TRAVAUX Martinique">
<meta property="og:type" content="website">
<meta property="og:locale" content="fr_FR">
<meta property="og:image" content="https://revotravaux.fr/og-image.jpg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta name="geo.region" content="MQ">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
<?php include __DIR__ . '/style.css'; ?>

/* Chantiers réels (REVO Pilotage) */
.live-head{text-align:center;max-width:640px;margin:0 auto 32px}.live-head h2{font-size:clamp(1.5rem,3vw,2rem);font-weight:800;margin:12px 0 8px}.live-head p{color:var(--text-2)}
.live-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:20px}
.live-card{background:var(--white);border:1px solid var(--border);border-radius:var(--radius-xl);overflow:hidden}
.live-photos{display:grid;gap:3px;grid-template-columns:2fr 1fr;grid-auto-rows:120px;aspect-ratio:auto}
.live-photos.n1{grid-template-columns:1fr;grid-auto-rows:280px}.live-photos.n2{grid-template-columns:1fr 1fr;grid-auto-rows:240px}
.live-photos.n3 .live-ph:first-child,.live-photos.n4 .live-ph:first-child{grid-row:span 2}
.live-photos.n4{grid-template-columns:2fr 1fr 1fr}.live-photos.n4 .live-ph:nth-child(4){grid-column:2/4}
.live-ph{padding:0;border:0;background:var(--warm);cursor:zoom-in;overflow:hidden;display:block}
.live-ph img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .5s}.live-ph:hover img{transform:scale(1.04)}
.live-body{padding:20px 24px 22px}.live-tags{display:flex;flex-wrap:wrap;gap:6px}.live-tags .real-tag{margin-bottom:8px}
.live-body h3{font-size:1.05rem;font-weight:700;margin-bottom:4px}.live-where{font-size:.8rem;color:var(--muted);font-weight:600}
.live-more{display:inline-flex;flex-wrap:wrap;gap:4px 8px;align-items:baseline;margin-top:12px;font-weight:700;font-size:.88rem;color:var(--gold);text-decoration:none}.live-more span{font-weight:500;font-size:.78rem;color:var(--muted)}
.live-box{position:fixed;inset:0;z-index:9999;background:rgba(10,10,10,.92);display:flex;align-items:center;justify-content:center;padding:20px}.live-box[hidden]{display:none}
.live-box img{max-width:100%;max-height:100%;border-radius:8px}.live-x{position:absolute;top:14px;right:16px;width:44px;height:44px;border-radius:50%;border:0;background:rgba(255,255,255,.15);color:#fff;font-size:1.2rem;cursor:pointer}
@media(max-width:800px){.live-grid{grid-template-columns:1fr}.live-photos{grid-auto-rows:100px}}
@media(max-width:800px){#real-grid{grid-template-columns:1fr!important}.real-card{grid-template-columns:1fr}.real-img{height:200px}}
</style>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5RPQHRD5"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div id="main-nav"></div>

<section class="page-hero"><div class="container page-hero-inner">
<div class="breadcrumb"><a href="index.html">Accueil</a><span>/</span><span>Réalisations</span></div>
<span class="badge badge-bronze" style="margin-bottom:14px">🏆 Nos chantiers</span>
<h1>Nos <span class="highlight">réalisations</span><br>en Martinique</h1>
<p>Rénovations complètes, cuisines, salles de bain, travaux énergétiques : réalisées en interne ou en sous-traitance pilotée, coordonnées de bout en bout.</p>
</div></section>

<?php if ($live_projects): ?>
<section class="section live-real"><div class="container">
<div class="live-head reveal">
<span class="badge badge-bronze">📸 Photos de nos chantiers</span>
<h2>Nos derniers chantiers, en vraies photos</h2>
<p>Prises sur place par nos équipes, avec l’accord de nos clients.</p>
</div>
<div class="live-grid">
<?php foreach ($live_projects as $lp):
  $where = trim(($lp['city'] ?? '') . (!empty($lp['year']) ? ' · ' . $lp['year'] : ''), ' ·');
  $n = count($lp['photos']); ?>
<article class="live-card reveal">
<div class="live-photos n<?= $n ?>">
<?php foreach ($lp['photos'] as $i => $ph): ?>
<button type="button" class="live-ph" data-full="<?= htmlspecialchars($ph['l']) ?>" aria-label="Agrandir la photo <?= $i + 1 ?>"><img src="<?= htmlspecialchars($ph['s']) ?>" alt="<?= htmlspecialchars($lp['title'] . ($where ? ' — ' . $where : '')) ?>" loading="lazy"></button>
<?php endforeach; ?>
</div>
<div class="live-body">
<div class="live-tags"><?php foreach (array_slice($lp['tags'] ?: [$lp['title']], 0, 3) as $t): ?><span class="real-tag"><?= htmlspecialchars($t) ?></span><?php endforeach; ?></div>
<h3><?= htmlspecialchars($lp['title']) ?></h3>
<?php if ($where): ?><p class="live-where">📍 <?= htmlspecialchars($where) ?></p><?php endif; ?>
<?php if (!empty($lp['more']) && $vitrine_url): ?><a class="live-more" href="<?= htmlspecialchars($vitrine_url) ?>" target="_blank" rel="noopener">Voir tout le chantier 🔒 <span>sur code, demandez-le-nous</span></a><?php endif; ?>
</div>
</article>
<?php endforeach; ?>
</div>
</div></section>
<div class="live-box" id="live-box" hidden><button type="button" class="live-x" aria-label="Fermer">✕</button><img alt=""></div>
<?php endif; ?>

<section class="section" style="background:var(--cream-2)"><div class="container">

<div class="filters">
<button class="filter-btn active" onclick="filterReal('all',this)">Tous (<?= count($projects) ?>)</button>
<?php foreach($cats as $k=>$v):$c=count(array_filter($projects,fn($p)=>$p['cat']===$k));if($c>0):?>
<button class="filter-btn" onclick="filterReal('<?=$k?>',this)"><?=$v?> (<?=$c?>)</button>
<?php endif;endforeach;?>
</div>

<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:20px" id="real-grid">
<?php
$modes = ['interne'=>'Réalisé en interne','sous-traitance'=>'Sous-traitance pilotée','amo'=>'Coordination AMO'];
foreach($projects as $p): $tag = $cats[$p['cat']] ?? $p['cat']; ?>
<a href="realisations/<?= htmlspecialchars($p['filename']) ?>" class="real-card reveal" data-cat="<?= htmlspecialchars($p['cat']) ?>" style="text-decoration:none;color:inherit">
<div class="real-img"><img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" loading="lazy" onerror="this.parentElement.style.background='var(--warm)';this.style.display='none'"></div>
<div class="real-body">
<span class="real-tag"><?= $tag ?></span>
<h3><?= htmlspecialchars($p['title']) ?></h3>
<p style="font-size:0.875rem;color:var(--text-2);line-height:1.7;margin-bottom:14px"><?= htmlspecialchars($p['summary']) ?></p>
<?php if($p['quote']):?><div style="font-size:0.84rem;font-style:italic;color:var(--text-2);border-left:2.5px solid var(--gold-l);padding-left:12px;line-height:1.6;margin-bottom:10px">"<?= htmlspecialchars($p['quote']) ?>"</div><?php endif;?>
<div style="font-size:0.78rem;color:var(--muted);font-weight:600"><?= htmlspecialchars($p['location']) ?><?php if($p['budget']):?> · <?= htmlspecialchars($p['budget']) ?><?php endif;?><?php if(!empty($p['mode']) && isset($modes[$p['mode']])):?> · <?= $modes[$p['mode']] ?><?php endif;?></div>
</div></a>
<?php endforeach;?>
</div>

<div style="text-align:center;margin-top:36px" class="reveal">
<a href="https://wa.me/596696381215?text=Bonjour%2C%20je%20souhaite%20démarrer%20mon%20projet%20de%20rénovation." target="_blank" class="btn btn-primary" style="font-size:1rem;padding:13px 28px">Démarrer mon projet</a>
</div>

</div></section>

<div id="main-footer"></div>
<script>
<?php include __DIR__ . '/shared.js'; ?>
</script>
<script>buildNav('realisations');buildFooter();addWA();injectSchema();
(function(){var box=document.getElementById('live-box');if(!box)return;var img=box.querySelector('img');
document.querySelectorAll('.live-ph').forEach(function(b){b.addEventListener('click',function(){img.src=b.dataset.full;img.alt=b.querySelector('img').alt;box.hidden=false;});});
function close(){box.hidden=true;img.removeAttribute('src');}
box.addEventListener('click',function(e){if(e.target!==img)close();});document.addEventListener('keydown',function(e){if(e.key==='Escape')close();});})();
function filterReal(cat,btn){
document.querySelectorAll('.filter-btn').forEach(function(b){b.classList.remove('active')});
btn.classList.add('active');
document.querySelectorAll('#real-grid .real-card').forEach(function(card){
card.style.display=(cat==='all'||card.getAttribute('data-cat')===cat)?'':'none';
});
}
</script>
</body>
</html>
