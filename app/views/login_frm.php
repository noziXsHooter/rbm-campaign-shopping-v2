<div class="container-fluid mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-8 col-10">
            <div class="d-flex text-center justify-content-center mb-4">
                <h2 class="text-white"><strong><?= APP_NAME ?></strong></h2>
            </div>
            <div class="card loginCard p-4">

                <div class="d-flex text-center justify-content-center my-4">
                    <h2 class="text-white"><strong>Login</h2>
                </div>

                <div class="row justify-content-center">
                    <div class="col-8">
                        <form action="?ct=main&mt=login_submit" method="post" novalidate>
                            <div class="mb-3">
                                <label for="text_username" class="form-label">Utilizador</label>
                                <input type="email" name="text_username" id="text_username" value="" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="text_password" class="form-label">Password</label>
                                <input type="password" name="text_password" id="text_password" class="form-control" required>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="submit" class="btn btn-primary-bg btn-secondary px-4">Entrar<i class="fa-solid fa-right-to-bracket ms-2"></i></button>
                            </div>

                        <!-- <div class="mb-3 text-center">
                                <a class="text-info" href="#">Esqueci minha da senha!</a>
                            </div> -->
                            
                        </form>
                        <div class="mb-3 text-center">
                            <a class="text-info" href="?ct=main&mt=register_frm">Registrar</a>
                        </div>
                        <?php if(!empty($validation_errors)): ?> 
                            <div class="alert alert-danger p-2 text-center">
                                <?php foreach($validation_errors as $error): ?>
                                    <div><?= $error ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>