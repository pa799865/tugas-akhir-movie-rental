<?php  
session_start();
require 'functions.php';


// cek cookie
// if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
//     $id = $_COOKIE['id'];
//     $key = $_COOKIE['key'];

    // ambil username berdasarkan id
    // $result = mysqli_query($conn,"SELECT username FROM users WHERE id = $id");
    // $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
//     if ($key === hash('sha256', $row['username'])) {
//         $_SESSION['login'] = true;
//     }
// }

// if ( isset($_SESSION["login"])) {
//     header("Location: dashboard.php");
//     exit;
// }



if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    // cek username
    if (mysqli_num_rows($result) === 1) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if($password === $row["password"]) {
            // cek session
            $_SESSION["login"] = true;

            header("Location: admin/dashboard.php");
            exit;
        }
    }
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Rental</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet">
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

  <!-- Icons -->
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

  <!-- My Style -->
  <link rel="stylesheet" href="css/style.css">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
  integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />


  <!-- AlpineJS -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- App -->
   <script src="src/app.js" async></script>

   <!-- midtrans -->
   <script type="text/javascript"
   src="https://app.sandbox.midtrans.com/snap/snap.js"
   data-client-key="SB-Mid-client-rQX-TQxOwEU8M1xi"></script>


    

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <script src="../assets/js/config.js"></script>
</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar" x-data>
    <a href="#" class="navbar-logo">Movie<span>Rental</span>.</a>

    <div class="navbar-nav">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#products">Produk</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="search-button"><i data-feather="search"></i></a>
      <a href="#" id="shopping-cart-button">
        <i data-feather="shopping-cart"></i>
        <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
      </a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- Search Form start -->
    <div class="search-form">
      <input type="search" id="search-box" placeholder="search here...">
      <label for="search-box"><i data-feather="search"></i></label>
    </div>
    <!-- Search Form end -->

    <!-- Shopping Cart start -->
    <div class="shopping-cart">
      <template x-for="(item, index) in $store.cart.items" x-keys="index">
        <div class="cart-item">
          <img :src="`img/menu/${item.img}`" :alt="item.name">
          <div class="item-detail">
            <h3 x-text="item.name"></h3>
            <div class="item-price">
              <span x-text="rupiah(item.price)"></span> &times;
              <button id="remove" @click="$store.cart.remove(item.id)">&minus;</button>
              <span x-text="item.quantity"></span>
              <button id="add" @click="$store.cart.add(item)">&plus;</button> <p>Hari</p> &equals;
              <span x-text="rupiah(item.total)"></span>
            </div>
          </div>
          <!-- <i data-feather="trash-2" class="remove-item"></i> -->
        </div>
      </template>
      <h4 x-show="!$store.cart.items.length" style="margin-top: 1rem;">Cart Is Empty</h4>
      <h4 x-show="$store.cart.items.length">Total : <span x-text="rupiah($store.cart.total)"></span></h4>
      <div class="form-container" x-show="$store.cart.items.length">
        <form action="" id="checkoutForm">
          <input type="hidden" name="items" x-model="JSON.stringify($store.cart.items)">
          <input type="hidden" name="total" x-model="$store.cart.total">
          <h5>Customer Detail</h5>
          <label for="name">
            <span>Name</span>
            <input type="text" id="name" name="name" required>
          </label>
          <label for="email">
            <span>Email</span>
            <input type="email" id="email" name="email" required>
          </label>
          <label for="phone">
            <span>Phone</span>
            <input type="number" id="phone" name="phone" required autocomplete="off">
          </label>
          <button class="checkout disabled" type="submit" id="checkout-button" value="checkout">Checkout</button>
        </form>
      </div>
    </div>
    <!-- Shopping Cart end -->

  </nav>
  <!-- Navbar end -->

  
  <!-- Hero Section start -->
  <section class="hero" id="home">
    
    <div class="mask-container">
      <?php if (isset($error)) : ?>
        <div class="alert-error" id="alertError">
          <div class="toast">
        <i class="fas fa-times-circle"></i>
          <p>Username Atau Password Salah!</p>
          </div>
          </div>

          <script>
        setTimeout(function() {
            var alertBox = document.getElementById("alertError");
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = "0";
                setTimeout(function() {
                    alertBox.style.display = "none";
                }, 500);
            }
        }, 5000);
    </script>
        <?php endif; ?>
    <video class="background-video" autoplay muted loop>
    <source src="./img/background-video-landscape.mp4" type="video/mp4">
  </video>
      <main class="content">

        <h1>Tempatnya Para <span>Pecinta Film</span></h1>
        <p>Solusi untuk hiburan anda dan keluarga.</p>
      </main>
    </div>
  </section>
  <!-- Hero Section end -->

  <!-- About Section start -->
  <section id="about" class="about">
    <h2><span>Tentang</span> Kami</h2>

    <div class="row">
      <div class="about-img">
        <!-- Nanti akan diisi logo -->
        <img src="img/" alt="Logo Movie Rental">
      </div>
      <div class="content">
        <h3>Tentang Movie Rental</h3>
        <p>Movie Rental adalah platform penyewaan film terpercaya yang menyediakan film berkualitas, mulai dari film lokal hingga film hollywood terbaik. Kami berkomitmen menghadirkan hiburan yang menyenangkan dan berkualitas tinggi untuk hiburan anda.</p>
        <p>Dengan seleksi film yang ketat dan harga kompetitif, kami memastikan Anda tetap terhibur. Kepercayaan pelanggan adalah prioritas utama kami, sehingga kami selalu siap menjadi mitra terbaik Anda dalam penyewaan film.</p>
      </div>
    </div>
  </section>
  <!-- About Section end -->

  <!-- Menu Section start -->
  <section id="menu" class="menu">
    <h2><span>Coming</span> Soon</h2>
    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium eveniet, facere commodi rem fugiat optio libero, debitis similique doloribus assumenda eum deserunt dolore explicabo eos veniam est? Unde, natus sequi?
    </p>

    <div class="row">
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">-  Petaka Gunung Gede  -</h3>
        <p class="menu-card-price">IDR 409K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Petaka Gunung Gede -</h3>
        <p class="menu-card-price">IDR 937K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Petaka Gunung Gede -</h3>
        <p class="menu-card-price">IDR 285K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Petaka Gunung Gede -</h3>
        <p class="menu-card-price">IDR 19K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Petaka Gunung Gede -</h3>
        <p class="menu-card-price">IDR 23K</p>
      </div>
      <div class="menu-card">
        <img src="img/menu/download.jpg" alt="Espresso" class="menu-card-img">
        <h3 class="menu-card-title">- Petaka Gunung Gede -</h3>
        <p class="menu-card-price">IDR 850K</p>
      </div>
    </div>
  </section>
  <!-- Menu Section end -->

  <!-- Products Section start -->
  <section class="products" id="products" x-data="products">
    <h2><span>Film</span> Kami</h2>
    <p>Berikut film-film yang tersedia beserta harga sewa peserta per hari.</p>

    <div class="row">
      <template x-for="(item, index) in items" x-key="index">
        <div class="product-card">
        <div class="product-icons">
          <a href="#" @click.prevent="$store.cart.add(item)">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#shopping-cart" />
            </svg>
          </a>
          <!-- nanti akan di pakai -->
          <!-- <a href="#" class="item-detail-button">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#eye" />
            </svg>
          </a> -->
        </div>
        <div class="product-image">
          <img :src="`img/menu/${item.img}`" :alt="item.name">
        </div>
        <div class="product-content">
          <h3 x-text="item.name"></h3>
          <div class="product-stars">
            <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
            <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
            <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
            <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
            <svg width="24" height="24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#star" />
            </svg>
          </div>
          <div class="product-price"><span x-text="rupiah(item.price)"></span></div>
        </div>
        </div>
      </template>
    </div>
  </section>
  <!-- Products Section end -->

  <!-- Contact Section start -->
  <section id="contact" class="contact">
    <h2><span>Kontak</span> Kami</h2>
    <p>Kami siap membantu bila anda ingin menyewa film.
    </p>

    <div class="row">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.321703733716!2d104.50754697423945!3d0.9040161628482005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d96d002b084c61%3A0xc1ca3a40c58adba6!2sROSE%20SEROJA!5e0!3m2!1sid!2sid!4v1737729905180!5m2!1sid!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map"></iframe>

      <form action="">
        <div class="input-group">
          <i data-feather="user"></i>
          <input type="text" placeholder="nama">
        </div>
        <div class="input-group">
          <i data-feather="mail"></i>
          <input type="text" placeholder="email">
        </div>
        <div class="input-group">
          <i data-feather="phone"></i>
          <input type="text" placeholder="no hp">
        </div>
        <button type="submit" class="btn" >Kirim Pesan</button>
      </form>

    </div>
  </section>
  <!-- Contact Section end -->

  <!-- Footer start -->
  <footer>
    <div class="socials">
      <!-- untuk sementara -->
      <a href="https://www.instagram.com/putraarya589/"><i data-feather="instagram"></i></a>
      <!-- <a href="#"><i data-feather="twitter"></i></a> -->
      <!-- <a href="#"><i data-feather="facebook"></i></a> -->
    </div>

    <div class="links">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Menu</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="floating-contact-form">
        <div class="form-container">
          <h3>Anda Seorang <span>Admin?</span> Silahkan <span>Login </span>Disini</h3>

          <form action="" method="post" >
            <div class="field-container">
              <i id="nameicon"></i>
              <label for="username">Username</label>
              <input class="form-input" type="text" id="name" placeholder="username" name="username" required />
            </div>
            
            <div class="field-container">
              <i id="emailicon"></i>
              <label for="password">Password</label>
              <input class="form-input" type="password" id="email" placeholder="Masukkan Password" name="password" required />
            </div>

            <input type="submit" name="login" value="login" />
          </form>
        </div>

        <div class="contact-icon">
          <i class="fas fa-sign-in-alt"></i>
        </div>
      </div>

    <div class="credit">
      <p>Created by <a href="">Kelompok Movie Rental</a>. | &copy; 2025.</p>
    </div>
  </footer>
  <!-- Footer end -->

  <!-- Modal Box Item Detail start -->
   <!-- untuk saat ini  belum di pakai -->
  <div class="modal" id="item-detail-modal">
    <div class="modal-container">
      <a href="#" class="close-icon"><i data-feather="x"></i></a>
      <div class="modal-content">
        <img src="img/products/1.jpg" alt="Product 1">
        <div class="product-content">
          <h3>Product 1</h3>
          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Provident, tenetur cupiditate facilis obcaecati
            ullam maiores minima quos perspiciatis similique itaque, esse rerum eius repellendus voluptatibus!</p>
          <div class="product-stars">
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star"></i>
          </div>
          <div class="product-price">IDR 30K <span>IDR 55K</span></div>
          <a href="#"><i data-feather="shopping-cart"></i> <span>add to cart</span></a>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Box Item Detail end -->

  <!-- Feather Icons -->
  <script>
    feather.replace()
  </script>

  <!-- My Javascript -->
  <script src="js/script.js"></script>
</body>

</html>