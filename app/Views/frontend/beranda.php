<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar Buat Blog</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?=base_url('assets/frontend/css/style.css')?>">
</head>
<body>
    <header>
        <div class="logo">Your Logo</div>
        <nav class="navbar" id="navbar">
            <ul class="menu">
                <li><a href="#">Home</a></li>
                <li><a href="#">Profil</a></li>
                <li><a href="#">Contact Us</a></li>
            </ul>
            <ul class="header-right">
                <li><a href="<?=base_url('login');?>">Login</a></li>
            </ul>
            <i class="fas fa-bars mobile-nav-toggle"></i>
        </nav>
    </header>

    <section id="hero">
        <p>The Blog</p>
        <h1>Writings from our team</h1>
        <p>The latest industry news, interviews, technologies and resources.</p>
    </section>
    <section id="content">
        <div class="content">
            <?= $post; ?>
        </div>
        
        <?=$pager; ?>
    </section>

    <footer id="footer">
        <p class="copy-right">
            pindipin - 2023
        </p>
    </footer>
    <script src="<?=base_url('assets/frontend/js/script.js');?>"></script>
</body>
</html>