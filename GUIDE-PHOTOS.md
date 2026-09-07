# Guide rapide — ajouter des photos sur le site REVO TRAVAUX

## 1. Réalisations (chantiers)
1. Copie `realisations/_TEMPLATE.html` → `realisations/mon-projet.html`
2. Dépose tes photos dans `/images/realisations/`
3. Modifie en haut du fichier les balises `project-date`, `project-cat`, `project-summary`,
   `project-image` (chemin `images/realisations/xxx.jpg`), `project-location`, `project-budget`,
   `project-quote`, `project-mode` (`interne`, `sous-traitance` ou `amo`)
4. Plus bas dans le fichier, remplace les 5 chemins de la galerie photo
   (`../images/realisations/...`)
5. Le projet apparaît automatiquement sur la page /realisations.php — rien d'autre à faire.

## 2. Avant / Après
1. Ouvre `avant-apres.html`
2. Dépose la photo "avant" dans `/images/avant-apres/avant/` et "après" dans `/images/avant-apres/apres/`
3. Duplique un bloc `<div class="aa-item">...</div>` et change les 2 chemins + le texte

## 3. Notre équipe
1. Ouvre `equipe.html`
2. Dépose les photos dans `/images/equipe/`
3. Duplique un bloc `<div class="equipe-card">...</div>` et change photo / nom / rôle / description

## Important
- Les 16 fiches de réalisations et les articles de blog déjà présents utilisent encore des
  photos de banque d'images (Unsplash) à titre d'exemple de mise en page — à remplacer par
  tes vraies photos, ou à supprimer si tu ne veux garder que du contenu réel.
- Les avis clients sur la page d'accueil sont des emplacements vides à connecter à ton profil
  Google Business dès que tu as des avis réels.
