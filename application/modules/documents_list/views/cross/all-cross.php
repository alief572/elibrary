<style>
    li p,
    td p {
        margin-bottom: 0;
    }
</style>

<?php if ($requirement) foreach ($requirement as $req) : ?>
    <h1 class="font-weight-bolder">Standard : <?= $req->name; ?></h1>
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th width="50">No</th>
                <th width="40%">Process</th>
                <th>Pasal</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($Data[$req->standard_id]) : ?>
                <?php $n = 0;
                foreach ($Data[$req->standard_id] as $k => $pro) : $n++; ?>
                    <tr>
                        <td class="tex-center"><?= $n; ?></td>
                        <td>
                            <h5><?= $ArrPro[$k]; ?></h5>
                        </td>
                        <td>
                            <ul class="pl-6">
                                <?php if ($pro) foreach ($pro as $pas) : ?>
                                    <li class="mb-0"><?= $pas->chapter; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
            <?php endforeach;
            endif; ?>
        </tbody>
    </table>
    <br>
    <br>
<?php endforeach; ?>