<!DOCTYPE HTML>

<html lang="">
	<head>
		<title>Astral by HTML5 UP12</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<noscript><link rel="stylesheet" href="assets/css/noscript.css" /></noscript>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


		<!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
        <!-- Bootstrap core CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.7/css/mdb.min.css" rel="stylesheet">
	</head>
	<body class="is-preload">

		<!-- Wrapper-->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">

						<!-- Me -->
							<article id="home" class="panel intro centered">
								<header>
									<h1>Welcome</h1>
								</header>

								<div class="text-center">
                                  <a href="" class="btn btn-deep-orange mt-4 " data-toggle="modal" data-target="#modalLoginForm">Login</a>
                                </div>

                                <div class="text-center">
                                  <a href="" class="btn btn-default mt-4 " data-toggle="modal" data-target="#modalRegisterForm">Register</a>
                                </div>

							</article>





						            <!-- Modal to login -->
									<div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                      aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold">Login</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body mx-3">
                                            <div class="md-form mb-5">
                                              <i class="far fa-address-book prefix grey-text"></i>
                                              <input type="text" id="username" class="form-control validate">
                                              <label  for="username">Username</label>
                                            </div>

                                            <div class="md-form mb-4">
                                              <i class="fas fa-lock prefix grey-text"></i>
                                              <input type="password" id="password" class="form-control validate">
                                              <label data-error="wrong password"   for="password">Password</label>
                                            </div>

                                          </div>
                                          <div class="modal-footer d-flex justify-content-center">
                                            <a href="home" class="btn btn-default">Login</a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>


                                    <!-- Modal to register -->
                                    <div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                      aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header text-center">
                                            <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body mx-3">
                                            <div class="md-form mb-4">
                                              <i class="fas fa-user prefix grey-text"></i>
                                              <input type="text" id="register-name" class="form-control validate">
                                              <label data-error="wrong" data-success="right" for="register-name">Your name</label>
                                            </div>


                                            <div class="md-form">
                                              <i class="far fa-address-book prefix grey-text"></i>
                                              <input type="text" id="register-username" class="form-control validate">
                                              <label data-error="wrong" data-success="right" for="register-username">Your username</label>
                                            </div>

                                            <div class="md-form mb-4">
                                              <i class="fas fa-lock prefix grey-text"></i>
                                              <input type="password" id="register-password" class="form-control validate">
                                              <label data-error="wrong" data-success="right" for="register-password">Your password</label>
                                            </div>

                                            <div class="md-form mb-4">
                                              <i class="fas fa-lock prefix grey-text"></i>
                                              <input type="password" id="register-password-confirm" class="form-control validate">
                                              <label data-error="wrong" data-success="right" for="register-password-confirm">Confirm your password</label>
                                            </div>

                                          </div>
                                          <div class="modal-footer d-flex justify-content-center">
                                            <a href="home" class="btn btn-deep-orange">Sign up</a>
                                          </div>
                                        </div>
                                      </div>
                                    </div>


















				<!-- Footer -->
					<div id="footer">
						<ul class="copyright">
							<li>&copy; Untitled.</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
						</ul>
					</div>

			</div>

		<!-- Scripts -->

		<!-- JQuery -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- Bootstrap tooltips -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
        <!-- Bootstrap core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <!-- MDB core JavaScript -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.7.7/js/mdb.min.js"></script>


			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

			<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	</body>
</html>