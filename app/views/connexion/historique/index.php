<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Payement des connexion</h2>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Nom Client</th>
                    <th>Prénom Client</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($historiques) && !empty($historiques)) {
                    foreach ($historiques as $histoire) {
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($histoire['nom']); ?></td>
                            <td><?php echo htmlspecialchars($histoire['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($histoire['date_debut']); ?></td>
                            <td><?php echo ($histoire['date_fin'] ? htmlspecialchars($histoire['date_fin']) : 'En cours'); ?></td>
                            <?php if ($histoire['id_poste'] == null) { ?>
                                <td>Sans poste</td>
                            <?php } else { ?>
                                <td>Avec poste</td>
                            <?php } ?>
                            <td>
                                <form action="/connexion/payer" method="post">
                                    <input type="hidden" name="id" value="<?php echo $histoire['id_historique_connection'] ?>">
                                    <button type="submit">Confirme le payement</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Aucune donnée trouvée</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>

        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>