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
        width: 100%;
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
        padding: 5px;
    }

    .text-center {
        text-align: center;
    }
</style>

<body>

    <!-- <table>
        <tbody>
            <tr>
                <td width="100"><strong>Company</strong></td>
                <td>:</td>
                <td>Company Name</td>
            </tr>
        </tbody>
    </table> -->
    <!-- <hr> -->
    <table class="bordered table">
        <thead>
            <tr>
                <th colspan="4" style="padding:10px 0px">
                    <h2>CROSS REFERENCE <?= strtoupper($Data->name); ?> PASAL TO PROCESS</h2>
                </th>
            </tr>
            <tr>
                <th width="50" class="text-center">No</th>
                <th width="350">Pasal</th>
                <th>Proses Terkait</th>
                <th width="150">Dokumen Lain</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $n = 0;
            foreach ($Detail as $dtl) : $n++; ?>
                <tr>
                    <td class="text-center"><?= $n; ?></td>
                    <td><?= $dtl->chapter; ?></td>
                    <td>
                        <ul>
                            <?php
                            if (isset($Procedure[$dtl->id])) {
                                $explode = explode(',', $Procedure[$dtl->id]);
                                if (isset($explode) && $explode) {
                                    foreach ($explode as $exp) {
                                        echo isset($list_procedure[$exp]) ? "<li>" . $list_procedure[$exp] . "</li>" : '';
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </td>
                    <td><?= isset($other_docs[$dtl->id]) ? ($other_docs[$dtl->id]) : ''; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>