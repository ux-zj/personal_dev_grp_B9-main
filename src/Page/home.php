<?php
require_once "../builder/ComponentGenerator.php";
require_once "../Api/api.php";
session_start();

$generator = new ComponentGenerator();
$api = new api();
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a5e8e2f662.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // TODO: REMEMBER TO MOVE TO SCRIP FILE
        var TxtType = function(el, toRotate, period) {
            this.toRotate = toRotate;
            this.el = el;
            this.loopNum = 0;
            this.period = parseInt(period, 10) || 2000;
            this.txt = '';
            this.tick();
            this.isDeleting = false;
        };

        TxtType.prototype.tick = function() {
            var i = this.loopNum % this.toRotate.length;
            var fullTxt = this.toRotate[i];

            if (this.isDeleting) {
                this.txt = fullTxt.substring(0, this.txt.length - 1);
            } else {
                this.txt = fullTxt.substring(0, this.txt.length + 1);
            }

            this.el.innerHTML = '<span class="wrap">'+this.txt+'</span>';

            var that = this;
            var delta = 200 - Math.random() * 100;

            if (this.isDeleting) { delta /= 2; }

            if (!this.isDeleting && this.txt === fullTxt) {
                delta = this.period;
                this.isDeleting = true;
            } else if (this.isDeleting && this.txt === '') {
                this.isDeleting = false;
                this.loopNum++;
                delta = 500;
            }

            setTimeout(function() {
                that.tick();
            }, delta);
        };

        window.onload = function() {
            var elements = document.getElementsByClassName('typewrite');
            for (var i=0; i<elements.length; i++) {
                var toRotate = elements[i].getAttribute('data-type');
                var period = elements[i].getAttribute('data-period');
                if (toRotate) {
                    new TxtType(elements[i], JSON.parse(toRotate), period);
                }
            }
            // INJECT CSS
            var css = document.createElement("style");
            css.type = "text/css";
            css.innerHTML = ".typewrite > .wrap { border-right: 0.08em solid #212529}";
            document.body.appendChild(css);
        };
    </script>
</head>

<?php
    $generator->navbar();
?>

<body>
    <main class="container">
        <!-- Hero, Create Tournament and Features -->
        <div class="container py-4">
            <div class="p-5 mb-4 bg-light rounded-3">
                <div class="container-fluid py-5">
                    <h1 class="display-5 fw-bold">Get together
                        <a href="" class="typewrite display-5 fw-bold" data-period="2000"
                           data-type='[ "with friends & family.", "as a team.", "as competitors." ]'>
                            <span class="wrap"></span>
                        </a>
                    </h1>

                    <p class="col-md-8 fs-4">Generate <strong><u>fair tournaments</u></strong> to your rules.</p>
                    <button class="btn btn-primary btn-lg" type="button">View Live Tournaments</button>
                </div>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-6">
                    <div class="h-100 p-5 text-white bg-dark rounded-3">
                        <h2>Create Tournament</h2>
                        <p></p>
                        <button onclick="window.location.href='createTournament.php'" class="btn btn-outline-light" type="button">Create Tournament</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h-100 p-5 bg-light border rounded-3">
                        <h2>Features</h2>
                        <p></p>
                        <button class="btn btn-outline-secondary" type="button">View all Features</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generated Tournament List -->
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="position-sticky" style="top: 2rem;">
                        <div class="p-4 mb-3 bg-light rounded">
                            <h4 class="fst-italic">Filter</h4>
                            <p class="mb-0">Filter buttons can go here.</p>
                            <ol class="list-unstyled">
                                <li><a href="#">Tournament Name</a></li>
                                <li><a href="#">Activity Name</a></li>
                                <li><a href="#">Date</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <?php
                        $tournaments = $api->getTournaments();
                        foreach($tournaments as $tournament){
                            $generator->setTournament($tournament);
                            $generator->tournament_card();
                        }
                    ?>
                </div>
            </div>
        </div>

        <footer class="pt-3 mt-4 text-muted border-top">
            B9 &copy; 2022
        </footer>

    </main>
</body>
</html>