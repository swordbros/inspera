// PRELOADER
  // let settings = {
  //   progressSize: 320,
  //   progressColor: '#ffffff',
  //   lineWidth: 2,
  //   lineCap: 'round',
  //   preloaderAnimationDuration: 800,
  //   startDegree: -90,
  //   finalDegree: 270
  // }

  // function setAttributes(elem, attrs) {

  //   for (let key in attrs) {
  //     elem.setAttribute(key, attrs[key]);
  //   }

  // }

  // let preloader = document.createElement('div'),
  //   canvas = document.createElement('canvas'),
  //   size;

  // (function () {

  //   let width = window.innerWidth,
  //     height = window.innerHeight;

  //   if (width > height) {

  //     size = Math.min(settings.progressSize, height / 2);

  //   } else {

  //     size = Math.min(settings.progressSize, width - 50);

  //   }

  // })();

  // setAttributes(preloader, {
  //   class: "preloader",
  //   id: 'preloader',
  //   style: 'transition: opacity ' + settings.preloaderAnimationDuration / 1000 + 's'
  // });
  // setAttributes(canvas, {
  //   class: 'progress-bar',
  //   id: 'progress-bar',
  //   width: settings.progressSize,
  //   height: settings.progressSize
  // });


  // preloader = document.getElementById('preloader');

  // let progressBar = document.getElementById('progress-bar'),
  //   images = document.images,
  //   imagesAmount = images.length,
  //   imagesLoaded = 0,
  //   barCtx = progressBar.getContext('2d'),
  //   circleCenterX = progressBar.width / 2,
  //   circleCenterY = progressBar.height / 2,
  //   circleRadius = circleCenterX - settings.lineWidth,
  //   degreesPerPercent = 3.6,
  //   currentProgress = 0,
  //   showedProgress = 0,
  //   progressStep = 0,
  //   progressDelta = 0,
  //   startTime = null,
  //   running;

  // (function () {

  //   return requestAnimationFrame
  //     || mozRequestAnimationFrame
  //     || webkitRequestAnimationFrame
  //     || oRequestAnimationFrame
  //     || msRequestAnimationFrame
  //     || function (callback) {
  //       setTimeout(callback, 1000 / 60);
  //     };

  // })();

  // Math.radians = function (degrees) {
  //   return degrees * Math.PI / 180;
  // };


  // progressBar.style.opacity = settings.progressOpacity;
  // barCtx.strokeStyle = settings.progressColor;
  // barCtx.lineWidth = settings.lineWidth;
  // barCtx.lineCap = settings.lineCap;
  // let angleMultiplier = (Math.abs(settings.startDegree) + Math.abs(settings.finalDegree)) / 360;
  // let startAngle = Math.radians(settings.startDegree);
  // document.body.style.overflowY = 'hidden';
  // preloader.style.backgroundColor = settings.preloaderBackground;


  // for (let i = 0; i < imagesAmount; i++) {

  //   let imageClone = new Image();
  //   imageClone.onload = onImageLoad;
  //   imageClone.onerror = onImageLoad;
  //   imageClone.src = images[i].src;

  // }

  // function onImageLoad() {

  //   if (running === true) running = false;

  //   imagesLoaded++;

  //   if (imagesLoaded >= imagesAmount) hidePreloader();

  //   progressStep = showedProgress;
  //   currentProgress = ((100 / imagesAmount) * imagesLoaded) << 0;
  //   progressDelta = currentProgress - showedProgress;

  //   setTimeout(function () {

  //     if (startTime === null) startTime = performance.now();
  //     running = true;
  //     animate();

  //   }, 10);

  // }

  // function animate() {

  //   if (running === false) {
  //     startTime = null;
  //     return;
  //   }

  //   let timeDelta = Math.min(1, (performance.now() - startTime) / settings.preloaderAnimationDuration);
  //   showedProgress = progressStep + (progressDelta * timeDelta);

  //   if (timeDelta <= 1) {


  //     barCtx.clearRect(0, 0, progressBar.width, progressBar.height);
  //     barCtx.beginPath();
  //     barCtx.arc(circleCenterX, circleCenterY, circleRadius, startAngle, (Math.radians(showedProgress * degreesPerPercent) * angleMultiplier) + startAngle);
  //     barCtx.stroke();
  //     requestAnimationFrame(animate);

  //   } else {
  //     startTime = null;
  //   }

  // }

  // function hidePreloader() {
  //   setTimeout(function () {
  //     $("body").addClass("page-loaded");
  //     locoScroll.update();
  //     document.body.style.overflowY = '';
  //   }, settings.preloaderAnimationDuration + 100);
  // }
  // var resizeTimer;