
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<style>
    .formsearch {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
    }

    #search {
        width: 320px;
    }

    .btn {
        cursor: pointer;
    }

    @media screen and (max-width: 720) {
        #search {
            width: 30%;
        }
    }
</style>
<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">File Manager</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Blog</li>
        <li class="breadcrumb-item">File Manager</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data File Manager</div>
                    <div class="ibox-tools">
                        <a onclick="reload_table()" class="refresh" data-toggle="tooltip" data-placement="top" title="reload data"><i class="fa fa-refresh"></i></a>
                        <?php if(enforce(2, 2)): ?>
                            <a class="" onclick="tambah_data()" data-toggle="tooltip" data-placement="top" title="tambah data"><i class="fa fa-plus-square"></i></a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="ibox-body">
                    <form action="" class="formsearch">
                        <?=csrf_field();?>
                        <div class="form-group" id="search">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="Search" name="search">
                                <div class="input-group-btn">
                                    <button class="btn btn-info" type="button" onclick="getList(1)"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row" id="divList">
                        
                    </div>
                    <hr>
                    <div id="pager"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

<div class="modal fade" id="modalfilemanager" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formfilemanager'], ['files_id' => '', 'method' => '']);?>
            <?=csrf_field();?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
					<div class="form-group">
                        <?=form_label('Name');?>
                        <?=form_input('val_files_name', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Description');?>
                        <?=form_textarea('val_files_desc', '', ['class' => 'form-control', 'rows' => 3]);?>
                    </div>
					<div class="form-group" id="divform_files_path">
                        <?=form_label('File', 'val_files_path');?>
                        <?=form_upload('val_files_path', '', ['class' => 'form-control', 'id' => 'val_files_path', 'onchange' => "readURL(this, 'img-preview-files_path');"]);?>
                    </div>
                    <div class="form-group">
                        <?=form_label('Doc Name', 'val_files_file');?>
                        <?=form_input('val_files_file', '', ['class' => 'form-control']);?>
                    </div>
                    <div class="form-group">
                        <?=form_label('Path', 'val_path');?>
                        <?=form_input('val_path', '', ['class' => 'form-control']);?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                </div>
            </div>
        <?=form_close();?>
    </div>
</div>
<?=$this->endSection();?>
<?=$this->section('js');?>
<script src="<?=base_url(); ?>/assets/alertifyjs/alertify.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/jquery-validation/dist/jquery.validate.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/moment/min/moment.min.js" type="text/javascript"> </script>
<script src="<?=base_url(); ?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"> </script>

<script>
    var table;
    var save_method;
    function getList(id) {
        $.ajax({
            type: "POST",
            url: "<?=base_url('/blog/filemanager/get_list');?>",
            data: {
                csrf_test_name: '<?=csrf_hash();?>',id: id,search: $('[name="search"]').val()
            },
            dataType: "JSON",
            success: function (response) {
                $('#divList').html(response.html);
                $('#pager').html(response.pager);
            },
            error: function(e) {
                console.log(e.responseText);
            },
        });
    }

    $(document).ready(function () {
        getList(1);
	});

    function reset_form() {
        var MValid = $("#formfilemanager");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        $('#img-old-files_path').attr('src', '<?=base_url('assets/admincast/dist/assets/img/image.jpg')?>');
        $('#divform_files_path').show();$('#divcol_files_path').show();$('#himage_files_path').hide();
    }

    <?php if(enforce(2, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalfilemanager').modal('show');
        $('#modalfilemanager .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="files_id"]').val(null);
        $('#formfilemanager .form-control').removeClass('form-view-detail');
        $('#formfilemanager .form-control').prop('disabled', false);
        $('#formfilemanager button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(2, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formfilemanager').valid();
        $('[name="method"]').val('update');
        $('#formfilemanager .form-control').removeClass('form-view-detail');
        $('#formfilemanager .form-control').prop('disabled', false);
        $('#formfilemanager button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/blog/filemanager');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalfilemanager').modal('show');
                $('#modalfilemanager .modal-title').text('Edit Data');
                $('[name="files_id"]').val(response.files_id);
                $('[name="val_files_name"]').val(response.files_name);
				$('[name="val_files_desc"]').val(response.files_desc);
				$('[name="val_path"]').val(response.files_path);
                $('[name="val_files_file"]').val(response.files_file);
            }
        });
    }
    <?php endif ?>

    <?php if(enforce(2, 4)): ?>
    function delete_data(id) {
        Swal.fire({
        title: 'Apa Anda Yakin?',
        text: "Anda Tidak Dapat Mengembalikan Data Yang Telah Di Hapus",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya Hapus !'
        }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "DELETE",
                url: "<?=base_url('/blog/filemanager')?>/"+id+'/delete_data',
                dataType: "json",
                success: function (response) {
                    if(response.errorCode == 1) {
                        Swal.fire(
                            'Deleted!',
                            'Data Berhasil Di Hapus.',
                            'success'
                        )
                    } else {
                        Swal.fire(
                            'Deleted Failed!',
                            'Data Gagal Di Hapus',
                            'warning'
                        )
                    }
                    getList();
                }
            });
        }
        })

    }
    <?php endif ?>

    $(function() {
        $('#formfilemanager').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_files_name: {
                required: true,
				remote: {
                    url: "<?=base_url('/blog/filemanager/files_name_exist'); ?>",
                    type: "post",
                    data: {
                        files_name: function() {
                            return $('[name="val_files_name"]').val();
                        },
                        files_id: function() {
                            return $('[name="files_id"]').val();
                        },
                        csrf_test_name: function() {
                            return $('meta[name=X-CSRF-TOKEN]').attr("content");
                        },
                    },
                },

				maxlength: 64
            },

			val_files_desc: {
                required: true,
				maxlength: 256
            },

            },
            messages: {
				val_files_name: {
                    required:'Name File harus diisi',remote: 'Name File sudah Ada, Tidak bisa di Input',maxlength: 'Name File Tidak Boleh Lebih dari 64 Huruf'
                },

				val_files_desc: {
                    required:'Description harus diisi',maxlength: 'Description Tidak Boleh Lebih dari 256 Huruf'
                },

            },
            highlight: function(e) {
                $(e).closest(".form-control").removeClass("is-valid").addClass("is-invalid");
            },
            unhighlight: function(e) {
                $(e).closest(".form-control").removeClass("is-invalid").addClass("is-valid");
            },
            submitHandler: function() {
                var url = '';
                if(save_method == 'update') {
                    url = "<?=base_url('/blog/filemanager/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/blog/filemanager/save_data');?>";
                }
                var formData = new FormData($($('#formfilemanager'))[0]);
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType: "JSON",
                    processData: false,
                    contentType:false,
                    cache : false,
                    success: function (response) {
                        if(response.errorCode == 1) {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                            getList();
                            $('#modalfilemanager').modal('hide');
                        } else {
                            alertify.set('notifier', 'position', 'top-right');
                            alertify.notify('<span><i class="fa fa-bell"></i> ' + response.errorMessage + '</span>', response.errorType, 5, function() {
                                console.log('dismissed');
                            });
                        }
                    },
                    error: function(jqXHR){
                        console.log(jqXHR.responseText);
                    }
                });
            }
        });
    });
</script>
<?=$this->endSection();?>
