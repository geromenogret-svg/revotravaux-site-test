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

@media(max-width:800px){#real-grid{grid-template-columns:1fr!important}.real-card{grid-template-columns:1fr}.real-img{height:200px}}
</style>
</head>
<body>
<div id="main-nav"></div>

<section class="page-hero"><div class="container page-hero-inner">
<div class="breadcrumb"><a href="index.html">Accueil</a><span>/</span><span>Réalisations</span></div>
<span class="badge badge-bronze" style="margin-bottom:14px">🏆 Nos chantiers</span>
<h1>Nos <span class="highlight">réalisations</span><br>en Martinique</h1>
<p>Rénovations complètes, cuisines, salles de bain, travaux énergétiques : réalisées en interne ou en sous-traitance pilotée, coordonnées de bout en bout.</p>
</div></section>

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
