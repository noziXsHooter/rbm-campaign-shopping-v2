<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- os meus clientes -->
        <div class="col-12 p-3 bg-white">
            <div class="col">
                <h3><strong>Clientes</strong></h3>
            </div>
            <div class="row">
                <div class="col">
                    <h5><i class="fa-solid fa-user-tie me-2"></i>Usuário: <strong><?= $user->name ?></strong></h5>
                </div>

                <div class="col">
                    <!--  Encoda o array para JSON e guarda no campo input   -->
                    <?php $json_data = json_encode($clients, JSON_UNESCAPED_UNICODE); ?>
    
                    <!--  Envia os dados por esse form/button -->
                    <form action="?ct=user&mt=export_csv" method="post">
                        <div class="col text-end">
                            <input type="hidden" name="data" value="<?php echo htmlentities($json_data); ?>">
                            <button type="submit" name="export_csv" class="btn btn-secondary">
                                <i class="fa-regular fa-file-excel me-2"></i>Exportar para CSV
                            </button>
                        </div>
                    </form>
                </div>

                <!--                 <div class="col text-end">
                    <a href="#" class="btn btn-secondary"><i class="fa-solid fa-upload me-2"></i></i>Carregar ficheiro</a>
                    <a href="?ct=agent&mt=new_client_frm" class="btn btn-secondary"><i class="fa-solid fa-plus me-2"></i>Novo cliente</a>
                </div> -->
            </div>
            <hr>
            <?php if (empty($clients)) : ?>
                <p class="my-5 text-center opacity-75">Não existem clientes registados.</p>
            <?php else : ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th class=" text-center">Nome</th>
                            <th class="w-25 text-center">Sexo</th>
                            <th class="w-25 text-center">Nascimento</th>
                            <th class="w-25 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client) : ?>
                            <tr>
                                <td class="text-center"><?= $client->name ?></td>
                                <td class="text-center"><?= $client->sex ?></td>
                                <td class="text-center"><?= $client->born_in ?></td>
                                <td class="text-center">
                                    <a href="?ct=coupon&mt=client_coupons&id=<?= $client->id ?>"><i class="fa-regular fa-pen-to-square me-2"></i>Cupons</a>
                                    <!--  <span class="mx-2 opacity-50">|</span> -->
                                    <!--                                 <a href="?ct=agent&mt=edit_client&id=<?= $client->id ?>"><i class="fa-regular fa-pen-to-square me-2"></i>Editar</a>
                                <span class="mx-2 opacity-50">|</span> -->
                                    <!--                                 <a href="?ct=agent&mt=delete_client&id=<?= $client->id ?>"><i class="fa-solid fa-trash-can me-2"></i>Eliminar</a> -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col">
                        <p class="mb-5">Total: <strong><?= count($clients) ?></strong></p>
                    </div>
                    <!--                     <div class="col text-end">
                        <a href="#" class="btn btn-secondary">
                            <i class="fa-regular fa-file-excel me-2"></i>Exportar para XLSX
                        </a>
                    </div> -->
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>