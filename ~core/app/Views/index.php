<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?></title>
  <link rel="icon" href="<?= base_url() ?>/assets/landing/images/favicon.png">
  <!-- Bootstrap CSS -->
  <link href="<?= base_url() ?>/assets/landing/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= base_url() ?>/assets/landing/css/all.css" rel="stylesheet">
  <!-- Slider CSS -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/owl.carousel.min.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/owl.theme.default.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/slick.css">
  <!-- Popup CSS -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/magnific-popup.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/meanmenu.min.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/font-family/font-family-one.css">
  <link rel="stylesheet" href="<?= base_url() ?>/assets/landing/css/preloader.css">
  <!-- KIDS CSS -->
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/landing/css/style.css">
  <!-- responsive css -->
  <link href="<?= base_url() ?>/assets/landing/css/responsive.css" rel="stylesheet">
  <style>
    .logo-wrapper {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 16px;
      /* jarak antar gambar */
      margin: 20px 0;
    }

    .logo-wrapper img {
      height: 40px;
      /* default untuk mobile */
      max-width: 100%;
      object-fit: contain;
    }

    /* Tablet: min-width 640px */
    @media (min-width: 640px) {
      .logo-wrapper img {
        height: 70px;
      }
    }

    /* Desktop: min-width 1024px */
    @media (min-width: 1024px) {
      .logo-wrapper img {
        height: 100px;
      }
    }

    @keyframes zoomRotate {

      0%,
      100% {
        transform: scale(0.5) rotate(0deg);
      }

      50% {
        transform: scale(1.2) rotate(9deg);
        /* membesar & sedikit memutar */
      }
    }

    .zoom-rotate-animation {
      animation: zoomRotate 2s ease-in-out infinite;
      transition: transform 0.3s;
    }

    @keyframes zoomRotateSwing {
      0% {
        transform: scale(0.5) rotate(0deg);
      }

      25% {
        transform: scale(2) rotate(10deg);
        /* sedikit membesar + putar kanan */
      }

      50% {
        transform: scale(0.7) rotate(0deg);
        /* kembali ke tengah */
      }

      75% {
        transform: scale(2) rotate(-10deg);
        /* sedikit membesar + putar kiri */
      }

      100% {
        transform: scale(0.5) rotate(0deg);
      }
    }

    .zoom-rotate-swing {
      animation: zoomRotateSwing 3s ease-in-out infinite;
      transition: transform 0.3s;
    }

    /* .img-slide {
      width: 50px;
      animation: zoomRotateSwing 3s ease-in-out infinite;
      transition: transform 0.3s;
    } */

    .img-slide {
      display: none;
      /* Default: tidak tampil */
      position: absolute;
      z-index: 5;
      /* Pastikan di atas header dan header-bottom */
      width: 100px;
      animation: zoomRotateSwing 3s ease-in-out infinite;
      transition: transform 0.3s;
      /* top: calc(80% + 0px); */
      /* default fallback jika JS tidak dipakai */
    }

    /* .img-slide {
      position: absolute;
      z-index: 2;
      width: 100px;
      animation: zoomRotateSwing 3s ease-in-out infinite;
      transition: transform 0.3s;
    } */

    @media (max-width: 768px) {
      .img-slide {
        display: block;
        /* Tampilkan hanya saat mobile */
      }

      .img-slide.one {
        left: 0;
      }

      .img-slide.two {
        right: 0;
      }
    }
  </style>
</head>

<body>
  <div id="loader-wrapper">
    <div id="loader"></div>
    <div class="loader-section section-left"></div>
    <div class="loader-section section-right"></div>
  </div>
  <!-- =======================
        end Preloader
======================== -->

  <header>
    <div class="menu">
      <div class="container-menu">

        <nav class="navbar navbar-expand-lg navbar-light">
          <a class="navbar-brand" href="<?= base_url() ?>"><img src="<?= base_url() ?>/assets/landing/images/<?= $header->logo ?>" alt=""></a>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 ">
              <li class="nav-item dropdown">
                <a class="nav-link active text-nowrap" aria-current="page" href="<?= base_url() ?>/">Beranda</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link active text-nowrap" aria-current="page" href="https://smkn1memhil.sch.id/tentang-kami/">Profil Sekolah</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link active text-nowrap" aria-current="page" href="<?= base_url() ?>/login">Kantin Digital</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link active text-nowrap" aria-current="page" href="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/minimarket" ?>">Milu Mart Digital</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link active text-nowrap" aria-current="page" href="<?= (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . "/BANK" ?>">Bank Mini</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active text-nowrap" aria-current="page" href="">Bisnis Lainnya</a>
              </li>
              <!-- <li class="nav-item"><a class="nav-link btn btn-primary kids-active-btn" href=""> LOGIN</a> </li> -->
            </ul>
          </div>
        </nav>
      </div>
    </div>
    <div id="headerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="false" style="z-index: 1;">
      <div class="carousel-fixed-text">
        <h2><?= $header->hero_text ?></h2>
      </div>
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#headerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="<?= base_url() ?>/assets/landing/images/slide1.jpg" class="d-block w-100" alt="Slide 1">
          <div class="carousel-caption d-block">
            <h3>Makanan Nyaman...</h3>
            <br>
            <p>Yaa di <font style="color: #EB6025; font-weight: bold">Kantin Digital</font> Beli Nya !</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="<?= base_url() ?>/assets/landing/images/slide2.jpg" class="d-block w-100" alt="Slide 2">
          <div class="carousel-caption d-block">
            <h3>Belanja Hemat....</h3>
            <br>
            <p>Pilihan Nya <font style="color: #EB6025; font-weight: bold">Milu Mart</font> Saja !</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="<?= base_url() ?>/assets/landing/images/slide33.jpg" class="d-block w-100" alt="Slide 3">
          <div class="carousel-caption d-block">
            <h3>Keuangan Terasa Lebih Mudah....</h3>
            <br>
            <p>Gunakan Bank Mini Kami ! Mudah, Akurat dan Tepat !</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#headerCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#headerCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    </div>
    <!-- end menu -->
    <!-- <div class="animation"><img src="<?= base_url() ?>/assets/landing/images/makanan_alfa.png" alt="" class="zoom-rotate-swing"></div> -->
    <!-- <div class="animation-two" style="background-color: green;"><img src="<?= base_url() ?>/assets/landing/images/7.gif" alt=""></div> -->
  </header>
  <div class=""><img src="<?= base_url() ?>/assets/landing/images/makanan1.png" alt="" class="img-slide one"></div>
  <div class=""><img src="<?= base_url() ?>/assets/landing/images/makanan2.png" alt="" class="img-slide two"></div>
  <!-- ====================================
        End Header Here
========================================= -->
  <div class="header-bottom">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
          <div class="header-box-single box-one">
            <div class="box-text">
              <?= $featured->text1 ?>
            </div>
          </div>
        </div>
        <!-- col-md-4 -->
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
          <div class="header-box-single box-two">
            <div class="box-text">
              <?= $featured->text2 ?>
            </div>
          </div>
        </div>
        <!-- col-md-4 -->
        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
          <div class="header-box-single box-three">
            <div class="box-text">
              <?= $featured->text3 ?>
            </div>
          </div>
        </div>
        <!-- col-md-4 -->
      </div>
    </div>
  </div>
  <!-- ====================================
        End Header Bottom Here
========================================= -->

  <!-- ====================================
        End About Here
========================================= -->

  <!-- ====================================
        End Counter Area Here
========================================= -->
  <section class="activities">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="animation"><img src="<?= base_url() ?>/assets/landing/images/makanan1.png" alt="" class="zoom-rotate-swing"></div>
          <div class="animation-two"><img src="<?= base_url() ?>/assets/landing/images/makanan2.png" alt="" class="zoom-rotate-swing"></div>
          <div class="section-title">
            <?= $kepala->nama ?>
          </div>
        </div>
        <div class="col-12 col-md-4 col-lg-4">

          <!-- <div class="activities-single-box d-flex justify-content-between" style="
          background-image: url('<?= base_url() ?>/assets/landing/images/people_eat.png');
          background-size: cover;
          background-position: center;
          background-repeat: no-repeat;
          height: auto;
          width: 100%;
          "> -->
          <div class="activities-single-box d-flex justify-content-between">
            <div class="activites-text">
              <?= $kepala->moto1 ?>
            </div>
            <div class="activities-img">
              <i class="fa-solid fa-futbol"></i>
            </div>
          </div>
          <div class="activities-single-box d-flex justify-content-between mt-4">
            <div class="activites-text">
              <?= $kepala->moto2 ?>
            </div>
            <div class="activities-img">
              <i class="fa-solid fa-heart"></i>
            </div>
          </div>
        </div>
        <!-- col-md-4 -->
        <div class="col-12 col-md-4 col-lg-4">
          <div class="activitics-main-img">
            <img src="<?= base_url() ?>/assets/landing/images/<?= $kepala->foto ?>" alt="">
          </div>
        </div>
        <!-- col-md-4 -->
        <div class="col-12 col-md-4 col-lg-4">
          <div class="activities-single-box d-flex justify-content-between m-left">
            <div class="activities-img">
              <i class="fa-solid fa-lightbulb"></i>
            </div>
            <div class="activites-text">
              <?= $kepala->moto3 ?>
            </div>
          </div>
          <div class="activities-single-box d-flex justify-content-between mt-4 m-left">
            <div class="activities-img">
              <i class="fa-solid fa-heart"></i>
            </div>
            <div class="activites-text">
              <?= $kepala->moto4 ?>
            </div>
          </div>
        </div>
        <!-- col-md-4 -->
      </div>
    </div>
    <!-- <div class="animation"><img src="<?= base_url() ?>/assets/landing/images/3.gif" alt=""></div>
    <div class="animation-two"><img src="<?= base_url() ?>/assets/landing/images/7.gif" alt=""></div> -->
    <!-- <div class="animation"><img src="<?= base_url() ?>/assets/landing/images/makanan1.png" alt="" class="zoom-rotate-swing"></div>
    <div class="animation-two"><img src="<?= base_url() ?>/assets/landing/images/makanan2.png" alt="" class="zoom-rotate-swing"></div> -->
  </section>
  <!-- ====================================
        End activities Area Here
========================================= -->

  <!-- ====================================
        End Our Classes Area Here
========================================= -->

  <!-- ====================================
        End Testimonial Area Here
========================================= -->
  <section class="gallery">
    <div class="container">
      <div class="grid">
        <div class="row">
          <div class="col-md-4 grid-item ct3 ct4">
            <div class="single-gallery-item">
              <div class="single-gallery-itemimg">
                <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto1 ?>" alt=""></a>
              </div>
              <div class="view">
                <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto1 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- item -->
          <div class="col-md-4 grid-item ct1">
            <div class="single-gallery-item ">
              <div class="single-gallery-itemimg">
                <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto2 ?>" alt=""></a>
              </div>
              <div class="view">
                <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto2 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
              </div>
            </div>
          </div>
          <!-- item -->
          <div class="col-md-4 grid-item ct3">
            <div class="single-gallery-item">
              <div class="single-gallery-itemimg">
                <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto3 ?>" alt=""></a>
              </div>
              <div class="view">
                <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto3 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
              </div>
            </div>
          </div>
          <?php if ($gallery->foto4 != '' || $gallery->foto4 != null) : ?>
            <div class="col-md-4 grid-item ct3">
              <div class="single-gallery-item">
                <div class="single-gallery-itemimg">
                  <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto4 ?>" alt=""></a>
                </div>
                <div class="view">
                  <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto4 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
                </div>
              </div>
            </div>
          <?php endif ?>
          <?php if ($gallery->foto5 != '' || $gallery->foto5 != null) : ?>
            <div class="col-md-4 grid-item ct3">
              <div class="single-gallery-item">
                <div class="single-gallery-itemimg">
                  <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto5 ?>" alt=""></a>
                </div>
                <div class="view">
                  <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto5 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
                </div>
              </div>
            </div>
          <?php endif ?>
          <?php if ($gallery->foto6 != '' || $gallery->foto6 != null) : ?>
            <div class="col-md-4 grid-item ct3">
              <div class="single-gallery-item">
                <div class="single-gallery-itemimg">
                  <a href=""><img src="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto6 ?>" alt=""></a>
                </div>
                <div class="view">
                  <a href="<?= base_url() ?>/assets/landing/images/<?= $gallery->foto6 ?>" class="port-view"><i class="fa-solid fa-eye"></i></a>
                </div>
              </div>
            </div>
          <?php endif ?>
          <!-- item -->
        </div>
        <!-- row -->

        <!-- row -->
        <!-- row -->
      </div>
    </div>
    <!-- <div class="g-bettarfly"><img src="<?= base_url() ?>/assets/landing/images/betterfly.png" alt=""></div>
    <div class="g-bettarfly-two"><img src="<?= base_url() ?>/assets/landing/images/betterfly.png" alt=""></div> -->
  </section>
  <!-- ====================================
        End Gellary Area Here
========================================= -->

  <!-- ====================================
        End Teachers Area Here
========================================= -->

  <!-- ====================================
        End Blog Area Here
========================================= -->
  <div class="call-to-action">
    <div class="container">
      <div class="row">
        <div class="col-md-12 clo-sm-12 cal-xxl-12">
          <div class="call-to-action-text">
            <div>
              <h3>Aplikasi E-CANTEEN dan E-MALL Layanan Bisnis Terpadu</h3>
              <p>@2025 - <a href="https://smkn1memhil.sch.id/" target="_blank" class="text-white">smkn1memhil.sch.id</a></p>
              <div class="footer-social">
                <ul>
                  <li><a href=""><i class="fa-brands fa-facebook-f"></i></a></li>
                  <li><a href=""><i class="fa-brands fa-linkedin-in"></i></a></li>
                  <li><a href=""><i class="fa-brands fa-twitter"></i></a></li>
                  <li><a href=""><i class="fa-brands fa-instagram"></i></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-8 clo-sm-12 cal-xxl-8">
          <div class="logo-wrapper">
            <img src="<?= base_url() ?>/assets/landing/images/smk.png" alt="SMK">
            <img src="<?= base_url() ?>/assets/landing/images/adiwiyata.png" alt="Adiwiyata">
            <img src="<?= base_url() ?>/assets/landing/images/kabupaten_mempawah.png" alt="Kabupaten Mempawah">
            <img src="<?= base_url() ?>/assets/landing/images/smk_bisa.png" alt="SMK Bisa">
            <img src="<?= base_url() ?>/assets/landing/images/wonderful_mempawah.png" alt="Wonderful Mempawah">
          </div>
          <!-- <div class="call-to-action-img"><img src="<?= base_url() ?>/assets/landing/images/calltoaction.png" alt=""></div> -->
        </div>
      </div>
    </div>
    <!-- <div class="call-shape-one"><img src="<?= base_url() ?>/assets/landing/images/white-cloud.png" alt=""></div> -->
    <div class="call-shape-two"><img src="<?= base_url() ?>/assets/landing/images/makanan_alfa.png" alt="" class="zoom-rotate-animation"></div>
    <!-- <div class="call-shape-three"><img src="<?= base_url() ?>/assets/landing/images/dots.png" alt=""></div> -->
    <!-- <div class="call-shape-four"><img src="<?= base_url() ?>/assets/landing/images/airplane.png" alt=""></div> -->
  </div>
  <!-- ====================================
        End Call to Action Area Here
========================================= -->


  <!-- Theme Need JS -->
  <script src="<?= base_url() ?>/assets/landing/js/jquery.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/jquery.sticky.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/waypoints.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/jquery.counterup.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/owl.carousel.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/slick.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/isotope.pkgd.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/jquery.magnific-popup.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/jquery.meanmenu.min.js"></script>
  <script src="<?= base_url() ?>/assets/landing/js/kids.js"></script>
</body>

</html>