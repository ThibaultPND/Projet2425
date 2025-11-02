<?php
require_once '../app/views/inc/header.php';

// $data['spots_json'] contient le JSON des spots
$spots = json_decode($data['spots_json'] ?? '[]', true);
?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Carte interactive</title>
<link rel="stylesheet" href="<?= URLROOT ?>/css/main.css">
<style>
  /* minimal CSS pour la map */
  .map-wrap { position: relative; width: 100%; max-width: 1200px; margin: 1rem auto; }
  .map-img { display:block; width:100%; height:auto; position: absolute; left:0; top:0; transition: opacity .25s ease; }
  .map-img.hidden { opacity: 0; pointer-events: none; }
  .map-base { position: relative; width:100%; }
  .map-svg { position: absolute; left:0; top:0; width:100%; height:100%; pointer-events: none; }
  .spot { cursor: pointer; pointer-events: auto; }
  .controls { text-align:center; margin-bottom:0.5rem; }
  .floor-btn { padding: 6px 10px; margin: 0 4px; cursor: pointer; }
</style>
</head>
<body>
  <div class="controls">
    <button class="floor-btn" data-floor="rdc">RDC</button>
    <button class="floor-btn" data-floor="rp1">R+1</button>
    <button class="floor-btn" data-floor="rp2">R+2</button>
    <button id="toggle-all" class="floor-btn">Afficher tous</button>
  </div>

  <div class="map-wrap" id="mapWrap">
    <div class="map-base" id="mapBase" style="padding-top:56.25%;"> <!-- ratio placeholder -->
      <!-- images servies par Spots::plan -->
      <img src="<?= URLROOT ?>/spots/plans/rdc" id="img-rdc" class="map-img" style="z-index:1" alt="RDC">
      <img src="<?= URLROOT ?>/spots/plans/rp1" id="img-rp1" class="map-img hidden" style="z-index:2" alt="R+1">
      <img src="<?= URLROOT ?>/spots/plans/rp2" id="img-rp2" class="map-img hidden" style="z-index:3" alt="R+2">

      <!-- SVG overlay (les cercles seront ajoutés dynamiquement) -->
      <svg id="mapSvg" class="map-svg" viewBox="0 0 100 100" preserveAspectRatio="xMinYMin meet">
        <!-- coords in percentage space (0..100) -->
      </svg>
    </div>
  </div>

<script>
(() => {
  // spots from PHP
  const spots = <?= json_encode($spots ?: []) ?>;
  // get DOM
  const svg = document.getElementById('mapSvg');
  const wrap = document.getElementById('mapWrap');
  const imgRdc = document.getElementById('img-rdc');
  const imgRp1 = document.getElementById('img-rp1');
  const imgRp2 = document.getElementById('img-rp2');

  // mapping floor -> image element
  const imgs = { rdc: imgRdc, rp1: imgRp1, rp2: imgRp2 };
  let activeFloor = 'rdc';
  let showAll = false;

  // ajuste le viewBox de SVG pour correspondre à 100x100 (pour utiliser pourcentages clairement)
  // (on a déjà viewBox 0 0 100 100, donc on place les points en x,y 0..100)

  // création des cercles
  function renderPoints() {
    // vide svg
    while (svg.firstChild) svg.removeChild(svg.firstChild);

    spots.forEach(spot => {
      const floor = spot.floor;
      // si on affiche seulement l'étage actif et showAll false -> skip les autres
      if (!showAll && floor !== activeFloor) return;

      const cx = parseFloat(spot.x_percent);
      const cy = parseFloat(spot.y_percent);

      // créer groupe <g> pour facilités d'interaction
      const g = document.createElementNS('http://www.w3.org/2000/svg','g');
      g.setAttribute('class','spot');
      g.setAttribute('data-id', spot.id);
      g.setAttribute('data-url', spot.url ?? '');
      g.setAttribute('data-label', spot.label ?? '');

      // cercle (taille relative)
      const c = document.createElementNS('http://www.w3.org/2000/svg','circle');
      c.setAttribute('cx', cx);
      c.setAttribute('cy', cy);
      c.setAttribute('r', 1.5); // rayon en % de viewBox (ajuste si besoin)
      c.setAttribute('fill', 'red');
      c.setAttribute('stroke', '#fff');
      c.setAttribute('stroke-width', 0.25);

      // label on hover (title)
      const title = document.createElementNS('http://www.w3.org/2000/svg','title');
      title.textContent = spot.label;
      c.appendChild(title);

      // faire pointer events active
      g.appendChild(c);
      svg.appendChild(g);

      // click handler (delegation possible)
      g.addEventListener('click', (e) => {
        const url = g.getAttribute('data-url');
        if (url) {
          // redirige vers l'URL du spot (ou ouvre modal)
          window.location.href = url;
        } else {
          alert(g.getAttribute('data-label') || 'Spot');
        }
      });
    });
  }

  // boutons étage
  document.querySelectorAll('.floor-btn[data-floor]').forEach(btn => {
    btn.addEventListener('click', () => {
      activeFloor = btn.getAttribute('data-floor');
      showAll = false;
      // toggle images
      Object.keys(imgs).forEach(f => {
        if (f === activeFloor) {
          imgs[f].classList.remove('hidden');
        } else {
          imgs[f].classList.add('hidden');
        }
      });
      renderPoints();
    });
  });

  document.getElementById('toggle-all').addEventListener('click', () => {
    showAll = !showAll;
    // when showAll we show all images semi-transparent
    Object.keys(imgs).forEach(f => {
      if (showAll) {
        imgs[f].classList.remove('hidden');
        imgs[f].style.opacity = 0.6;
      } else {
        imgs[f].style.opacity = 1;
        // hide non active
        if (f !== activeFloor) imgs[f].classList.add('hidden');
      }
    });
    renderPoints();
  });

  // responsive : adjust padding-top to keep image ratio if you know it, else calculate
  // here we attempt to set container aspect ratio from base image natural ratio
  function setAspectFromImage(img) {
    if (!img.naturalWidth) {
      img.addEventListener('load', () => setAspectFromImage(img));
      return;
    }
    const ratio = (img.naturalHeight / img.naturalWidth) * 100;
    document.getElementById('mapBase').style.paddingTop = ratio + '%';
  }
  // set on first image load
  setAspectFromImage(imgRdc);

  // initial render
  renderPoints();

})();
</script>
</body>
</html>

<?php require_once '../app/views/inc/footer.php'; ?>
