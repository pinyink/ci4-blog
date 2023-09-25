<?=$this->extend('frontend/template');?>
<?=$this->section('content');?>
<section id="hero">
    <h2><?=$detail['post_title'];?></h2>
    <div class="userby">
        <span>
            <i class="fa fa-user-alt"></i> <?=$detail['profil_firstname'].' '.$detail['profil_lastname'];?>
        </span>
        <span>
            <i class="fa fa-clock"></i> <?=date('d-M-Y', strtotime($detail['post_created_at']))?>
        </span>
    </div>
</section>

<section id="content">
    <div class="content">
        <div class="blog">
            <div class="blog-detail">
                <img src="<?=base_url($detail['post_image'])?>" alt="" class="img-blog">
                <?php foreach($body as $value): ?>
                    <<?=$value['post_body_categori']?>><?=$value['post_body_content']?></<?=$value['post_body_categori']?>>
                <?php endforeach ?>
            </div>
            <div class="blog-aside">
                <h1>Recent Post</h1>
                <?php foreach($recent as $r): ?>
                    <div class="box-aside">
                        <img src="<?=base_url($r['post_image']);?>" alt="">
                        <div class="box-aside-body">
                            <h3><?=$r['post_title'];?></h3>
                            <span><?=date('d M Y', strtotime($r['post_created_at']))?></span>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>
<?=$this->endSection();?>