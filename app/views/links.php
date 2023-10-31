<?php ob_start(); ?>
<main>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="mb-3">
                    <form action="" method="post">
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Stack overflow" />
                                    <label for="title">Titre</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="url" class="form-control" id="url" name="url" placeholder="https://stackoverflow.com" />
                                    <label for="url">Lien</label>
                                </div>
                            </div>
                            <div class="col-md-auto d-flex">
                                <button class="btn btn-primary btn-lg">Ajouter</button>
                            </div>
                        </div>
                    </form>
                </div>
                <ul class="list-group">
                    <?php if (count($links) > 0) { ?>
                        <?php foreach ($links as $link) { ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="<?= $link->url ?>"><?= $link->title ?></a>
                                <span>
                                    <i class="fa-regular fa-pen-to-square me-1 text-warning"></i>
                                    <i class="fa-solid fa-trash ms-1 text-danger"></i>
                                </span>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</main>
<?php $content = ob_get_clean();
include __DIR__ . '/layout.php'; ?>