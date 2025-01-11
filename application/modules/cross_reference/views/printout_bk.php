<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cross Reference</title>
</head>
<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }

    table {
        border-collapse: collapse;
    }

    table.bordered tr td,
    table.bordered tr th {
        border: 1px solid #aaa;
    }

    table tr th {
        padding: 5px;
        background-color: #eaeaea;
    }

    table tr td {
        padding: 10px;
    }
</style>

<body>

    <table>
        <tbody>
            <tr>
                <td width="100"><strong>Company</strong></td>
                <td>:</td>
                <td>Company Name</td>
            </tr>
        </tbody>
    </table>
    <hr>
    <h2>Standard : <?= $crossStd->name; ?></h2>

    <?php if ($lsProcedure) : ?>
        <table class="bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th width="180">Pasal</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php $n = 0;
                foreach ($lsProcedure as $p) : $n++;
                    if ($DataStd[$p]) : ?>
                        <tr>
                            <td colspan="3">
                                <strong><?= $n . ". " . $procedures[$p]->name; ?></strong>
                            </td>
                        </tr>
                        <?php $i = 0;
                        foreach ($DataStd[$p] as $dt) : $i++; ?>
                            <tr>
                                <td><?= $i; ?></td>
                                <td><?= $dt->chapter; ?></td>
                                <td>
                                    <?php if ($dt->desc_indo) : ?>
                                        <strong>Indonesian:</strong>
                                        <?= $dt->desc_indo; ?>
                                    <?php endif; ?>
                                    <br>
                                    <?php if ($dt->desc_eng) : ?>
                                        <strong>English:</strong>
                                        <?= $dt->desc_eng; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                    <?php endforeach;
                    endif; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <span>~ Not available data ~</span>
    <?php endif; ?>
</body>

</html>