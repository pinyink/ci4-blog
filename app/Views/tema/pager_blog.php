<div class="paging">
    <?php if ($pager->hasPrevious()) : ?>
        <div class="paging-previous">
            <a href="<?= $pager->getPrevious() ?>"><i class="fa fa-arrow-left"></i> Previous</a>
        </div>
    <?php endif ?>
    <div class="paging-number">
        <ul class="number">
            <?php foreach ($pager->links() as $link): ?>
                <li class="<?= $link['active'] ? 'active' : '' ?>"><a href="<?= $link['uri'] ?>" class="number-item"></a><?= $link['title'] ?></li>
            <?php endforeach ?>
        </ul>
    </div>
    <?php if ($pager->hasNext()) : ?>
        <div class="paging-next">
            <a href="<?= $pager->getNext() ?>">Next <i class="fa fa-arrow-right"></i></a>
        </div>
    <?php endif ?>
</div>