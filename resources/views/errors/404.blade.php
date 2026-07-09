<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
    
    <style>
        .page_404 {
            padding: 40px 0;
            background: #fff;
            font-family: 'Arvo', serif;
        }

        .page_404 img {
            width: 100%;
        }

        .four_zero_four_bg {
            background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
            height: 400px;
            background-position: center;
            background-repeat: no-repeat;
        }

        .four_zero_four_bg h1 {
            font-size: 80px;
            margin-top: 0px;
        }

        .four_zero_four_bg h3 {
            font-size: 80px;
        }

        .link_404 {
            color: #fff !important;
            padding: 10px 20px;
            background: #39ac31; 
            margin: 20px 0;
            display: inline-block;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .link_404:hover {
            background: #2e8b28;
            text-decoration: none;
        }

        .contant_box_404 {
            margin-top: -50px;
        }
        
        .tarsius-alert {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <section class="page_404">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        
                        <div class="four_zero_four_bg">
                            <h1 class="text-center">404</h1>
                        </div>
                        
                        <div class="contant_box_404">
                            <h3 class="h2">Look like you're lost</h3>
                            <p class="tarsius-alert">Halaman yang Anda tuju sedang tidak tersedia!</p>
                            
                            <a href="{{ url('/dashboard') }}" class="link_404">Go to Home</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>