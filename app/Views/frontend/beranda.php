<?=$this->extend('frontend/template');?>
<?=$this->section('content');?>
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
<?=$this->endSection();?>