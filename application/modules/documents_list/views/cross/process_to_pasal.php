<style>
    li p,
    td p {
        margin-bottom: 0;
    }
</style>
<h3>Standard : <?= $requirement->name; ?></h3>
<table class="table table-bordered table-sm">
    <thead class="table-light">
        <tr>
            <th width="50">No</th>
            <th width="40%">Process</th>
            <th>Pasal</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($procedures) : ?>
            <?php $n = 0;
            foreach ($procedures as $pro) : $n++; ?>
                <tr>
                    <td class="tex-center"><?= $n; ?></td>
                    <td>
                        <h5><?= $pro->name; ?></h5>
                    </td>
                    <td>
                        <ul class="pl-6">
                            <?php if ($Data[$pro->id]) foreach ($Data[$pro->id] as $pas) : ?>
                                <li class="mb-0"><?= $pas->chapter; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                </tr>
        <?php endforeach;
        endif; ?>
    </tbody>
</table>