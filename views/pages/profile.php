<?php
?>
<style>
    .custom-input {
        border: none;
        border-bottom: 1px solid grey;
    }

    .custom-input:active {
        border: none;
        border-bottom: 1px solid grey;
    }

    .custom-input:focus {
        border: none;
        border-bottom: 1px solid grey;
    }
</style>
<div class="mt-4">
    <h3>Profile</h3>
    <form id="update-profile" enctype="multipart/form-data" action="<?= base_url('auth/updateprofile') ?>" method="post">
        <div class="row mt-4" style="row-gap: 10px;">
            <div class="col-sm-12 col-md-4">
                <div class="profile-pic">
                    <label class="-label" for="file">
                        <span class="fas fa-camera"></span>
                        <span>Change Image</span>
                    </label>
                    <input accept="image/*" name="photo" id="file" type="file" />
                    <img style="" id="output" src="<?= staticUrl('img/profile/' . $photo) ?>" alt="Poto profile">

                </div>
            </div>
            <div class="col-sm-12 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td>Role</td>
                                    <td>
                                        <input type="text" name="role" value="<?= $role ?>" readonly id="role" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Username</td>
                                    <td>
                                        <input type="text" name="username" value="<?= $username ?>" readonly id="username" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nama Lengkap</td>
                                    <td>
                                        <input type="text" name="nama_lengkap" value="<?= $nama_lengkap ?>" id="nama_lengkap" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>
                                        <input type="email" name="email" value="<?= $email ?>" id="email" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>No.Hp <span class="symbol-required"></span></td>
                                    <td>
                                        <input type="text" name="no_hp" value="<?= $no_hp ?>" id="hp" class="form-control custom-input">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>
                                        <textarea name="alamat" class="form-control" rows="5" id="alamat"><?= $alamat ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Password <small>(isi jika ingin merubah password)</small></td>
                                    <td>
                                        <input minlength="8" class="form-control custom-input" type="password" name="password" id="password">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <div style="float: right;" class="">
                            <button type="submit" class="btn btn-info">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function() {
        $("#file").on('change', function(event) {
            var image = document.getElementById("output");
            console.log(event.target.files);
            image.src = URL.createObjectURL(event.target.files[0]);
        });
        $("#update-profile").initFormAjax({
        sebelumSubmit: function(){
            showLoading();
        },
        submitSuccess: function(res){
            endLoading();

            if (typeof(res) == 'string')
                res = JSON.parse(res);

            makeToast({
                title: 'Berhasil',
                message: res.message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                hancurkan: true,
                wrapper: 'body',
                delay: 5000
            });

            setTimeout(function(){
                location.reload();
            }, 2000);
        },
        submitError: function(res){
            endLoading();
            if (typeof (res) == 'string')
                res = JSON.parse(res);

            var message = "Sumbit Failed";

            if (res.message)
                defaultCnfigToast.message = res.message;
            else if (res.responseJSON.message)
                defaultCnfigToast.message = res.responseJSON.message;

            makeToast({
                title: 'Gagal',
                message: message,
                id: 'defaut-config',
                cara_tempel: 'after',
                autohide: true,
                show: true,
                hancurkan: true,
                wrapper: 'body',
                delay: 5000
            })
        }
       });
    })
</script>