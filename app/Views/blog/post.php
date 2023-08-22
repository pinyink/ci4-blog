
<?= $this->extend('tema/tema'); ?> 
<?=$this->section('css');?>
<!-- Data Table CSS -->
<link href="<?=base_url();?>/assets/alertifyjs/css/alertify.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/alertifyjs/css/themes/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/DataTables/datatables.min.css" rel="stylesheet" type="text/css" />
<link href="<?=base_url();?>/assets/admincast/dist/assets/vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />

<?=$this->endSection();?>

<?=$this->section('content'); ?>
<div class="page-heading">
    <h1 class="page-title">Post</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?=base_url('home');?>"><i class="fa fa-home font-20"></i></a>
        </li>
        <li class="breadcrumb-item">Blog</li>
        <li class="breadcrumb-item">Post</li>
    </ol>
</div>

<!-- Container -->
<div class="page-content fade-in-up">
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title">Data Post</div>
                    <div class="ibox-tools">
                        <a onclick="reload_table()" class="refresh" data-toggle="tooltip" data-placement="top" title="reload data"><i class="fa fa-refresh"></i></a>
                        <?php if(enforce(3, 2)): ?>
                            <a class="" onclick="tambah_data()" data-toggle="tooltip" data-placement="top" title="tambah data"><i class="fa fa-plus-square"></i></a>
                        <?php endif ?>
                    </div>
                </div>
                <div class="ibox-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="15%">Action</th>
                                    <th width="10%">No</th>
									<th style="width: 18.75%">Url</th>
									<th style="width: 18.75%">Title</th>
									<th style="width: 18.75%">Desc</th>
									<th style="width: 18.75%">Visited</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->
</div>
<!-- /Container -->

<div class="modal fade" id="modalpost" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <?=form_open('', ['id' => 'formpost'], ['post_id' => '', 'method' => '']);?>
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
                        <?=form_label('Url');?>
                        <?=form_input('val_post_url', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Title');?>
                        <?=form_input('val_post_title', '', ['class' => 'form-control'], 'text');?>
                    </div>
					<div class="form-group">
                        <?=form_label('Desc');?>
                        <?=form_textarea('val_post_desc', '', ['class' => 'form-control', 'rows' => 3]);?>
                    </div>
					<div class="form-group" id="divform_post_image">
                        <?=form_label('Image', 'val_post_image');?>
                        <?=form_upload('val_post_image', '', ['class' => 'form-control', 'id' => 'val_post_image', 'accept' => ".png,.jpg,.jpeg", 'onchange' => "readURL(this, 'img-preview-post_image');"]);?>
                    </div>
                    <div class="row">
                        <div class="col-md-6" id="divimage_post_image">
                            <h6 id="himage_post_image">Image</h6>
                            <img src="<?=base_url('assets/admincast/dist/assets/img/image.jpg') ?>" alt="" class="img img-thumbnail img-preview " id="img-preview-post_image" style="width: 100px; height: 100px;">
                        </div>
                        <div class="col-md-6" id="divcol_post_image">
                            <img src="<?=base_url('assets/admincast/dist/assets/img/image.jpg') ?>" alt="" class="img img-thumbnail img-preview" id="img-old-post_image" style="width: 100px; height: 100px;">
                        </div>
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
    $(document).ready(function () {
        table = $('#datatable').DataTable({
                    scrollCollapse: true,
                    responsive: true,
                    autoWidth: false,
                    language: { search: "",
                        searchPlaceholder: "Search",
                        sLengthMenu: "_MENU_items"
                    },
                    "order": [],
                    "ajax": {
                        "url": "<?php echo base_url('/blog/post/ajax_list') ?>",
                        "headers": {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        "type": "POST",
                        "data": {<?=csrf_token();?>: '<?=csrf_hash()?>'},
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(jqXHR.responseText);
                        }
                    },
                    //optional
                    "columnDefs": [{
                        "targets": [0, 1],
                        "orderable": false,
                    }, ],
                });
    });
    
    function reload_table() {
        table.ajax.reload(null, false);
    }

    $(document).ready(function () {
        
	});
    

    function reset_form() {
        var MValid = $("#formpost");
        MValid[0].reset();
        MValid.find(".is-invalid").removeClass("is-invalid");
        MValid.find(".is-valid").removeClass("is-valid");
        $('#img-old-post_image').attr('src', '<?=base_url('assets/admincast/dist/assets/img/image.jpg')?>');
        $('#divform_post_image').show();
        $('#divcol_post_image').show();
        $('#himage_post_image').hide();
    }

    function lihat_data(id) {
        reset_form();
        save_method = 'update';
        $('#formpost').valid();
        $('[name="method"]').val('update');
        $('#formpost .form-control').addClass('form-view-detail');
        $('#formpost .form-control').prop('disabled', true);
        $('#formpost button[type="submit"]').hide();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/blog/post');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalpost').modal('show');
                $('#modalpost .modal-title').text('Detail Data');
                $('[name="post_id"]').val(response.post_id);
                $('#divform_post_image').hide();$('#divcol_post_image').hide();$('#himage_post_image').show();$('[name="val_post_url"]').val(response.post_url);
				$('[name="val_post_title"]').val(response.post_title);
				$('[name="val_post_desc"]').val(response.post_desc);
				$('#img-old-post_image').attr('src', '<?=base_url('')?>/'+response.post_image);
				$('#img-preview-post_image').attr('src', '<?=base_url('assets/admincast/dist/assets/img/image.jpg')?>');
				
            }
        });
    }

    <?php if(enforce(3, 2)): ?>
    function tambah_data() {
        save_method = 'save';
        reset_form();
        $('#modalpost').modal('show');
        $('#modalpost .modal-title').text('Tambah Data');
        $('[name="method"]').val('save');
        $('[name="post_id"]').val(null);
        $('#formpost .form-control').removeClass('form-view-detail');
        $('#formpost .form-control').prop('disabled', false);
        $('#formpost button[type="submit"]').show();
    }
    <?php endif ?>

    <?php if(enforce(3, 3)): ?>
    function edit_data(id) {
        reset_form();
        save_method = 'update';
        $('#formpost').valid();
        $('[name="method"]').val('update');
        $('#formpost .form-control').removeClass('form-view-detail');
        $('#formpost .form-control').prop('disabled', false);
        $('#formpost button[type="submit"]').show();
        $.ajax({
            type: "GET",
            url: "<?=base_url('/blog/post');?>/"+id+'/get_data',
            dataType: "JSON",
            success: function (response) {
                $('#modalpost').modal('show');
                $('#modalpost .modal-title').text('Edit Data');
                $('[name="post_id"]').val(response.post_id);
                $('[name="val_post_url"]').val(response.post_url);
				$('[name="val_post_title"]').val(response.post_title);
				$('[name="val_post_desc"]').val(response.post_desc);
				$('#img-old-post_image').attr('src', '<?=base_url('')?>/'+response.post_image);
				$('#img-preview-post_image').attr('src', '<?=base_url('assets/admincast/dist/assets/img/image.jpg')?>');
				
            }
        });
    }
    <?php endif ?>

    <?php if(enforce(3, 4)): ?>
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
                url: "<?=base_url('/blog/post')?>/"+id+'/delete_data',
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
                    reload_table();
                }
            });
        }
        })

    }
    <?php endif ?>

    $(function() {
        $('#formpost').validate({
            errorClass: "invalid-feedback",
            rules: {
			val_post_url: {
                required: true,
				remote: {
                    url: "<?=base_url('/blog/post/post_url_exist'); ?>",
                    type: "post",
                    data: {
                        post_url: function() {
                            return $('[name="val_post_url"]').val();
                        },
                        post_id: function() {
                            return $('[name="post_id"]').val();
                        },
                        csrf_test_name: function() {
                            return $('meta[name=X-CSRF-TOKEN]').attr("content");
                        },
                    },
                },

				maxlength: 128
            },

			val_post_title: {
                required: true,
				maxlength: 256
            },

			val_post_desc: {
                required: true,
				maxlength: 256
            },

			val_post_image: {
                
            },

            },
            messages: {
				val_post_url: {
                    required:'Url harus diisi',remote: 'Url sudah Ada, Tidak bisa di Input',maxlength: 'Url Tidak Boleh Lebih dari 128 Huruf'
                },

				val_post_title: {
                    required:'Title harus diisi',maxlength: 'Title Tidak Boleh Lebih dari 256 Huruf'
                },

				val_post_desc: {
                    required:'Desc harus diisi',maxlength: 'Desc Tidak Boleh Lebih dari 256 Huruf'
                },

				val_post_image: {
                    
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
                    url = "<?=base_url('/blog/post/update_data');?>";
                }
                if(save_method == 'save') {
                    url = "<?=base_url('/blog/post/save_data');?>";
                }
                var formData = new FormData($($('#formpost'))[0]);
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
                            reload_table();
                            $('#modalpost').modal('hide');
                        } else {
                            toast_error(response.errorMessage);
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
