<div class="fixed-background"></div>
<main>
    <div class="container">
        <div class="row h-100">
            <div class="col-12 col-md-10 mx-auto my-auto">
                <div class="card auth-card">
                    <div class="position-relative image-side ">
                        <p class=" text-white h2">INI ADALAH HALAMAN ERROR</p>
                        <p class="text-white mb-0">Jika melihat halaman ini berarti ada yang salah</p>
                    </div>
                    <div class="form-side">
                        <div class="text-center">
                            <a href="Dashboard.Default.html">
                                <!-- <span class="logo-single"></span> -->
                            </a>

                            <h6 class="mb-4"><?= $message ?></h6>
                            <p class="mb-0 text-muted text-small mb-0">Error code</p>
                            <p class="display-1 font-weight-bold mb-5">
                                <?= $code ?>
                            </p>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-primary btn-lg btn-shadow">DASHBOARD</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>