<div class="container-fluid">
    <div class="row justify-content-center">
        <!-- os meus clientes -->
        <div class="col-12 p-3 bg-white">
                <div class="col">
                    <h3><strong>Meus Cupons</strong></h3>
                </div>
                <div class="row">
                <div class="col">
                    <h5><i class="fa-solid fa-user-tie me-2"></i>Usuário: <strong><?= $user->name ?></strong></h5>
                </div>

                <div class="col">
                    <!--  Encoda o array para JSON e guarda no campo input   -->
                    <?php $json_data = json_encode($my_coupons, JSON_UNESCAPED_UNICODE); ?>
    
                    <!--  Envia os dados por esse form/button -->
                    <form action="?ct=coupon&mt=export_csv&id=my_coupons" method="post">
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
            <?php if(empty($my_coupons) ): ?>
                <p class="my-5 text-center opacity-75">Não existem cupons registados.</p>
            <?php else: ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Código</th>
                            <th class="text-center">Valor</th>
                            <th class="text-center">Loja</th>
                            <th class="text-center">Data</th>
                            <th class="text-center">Status</th>
<!--                             <th></th> -->
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($my_coupons as $coupon): ?>
                        <tr>
                            <td><?= $coupon->code ?></td>
                            <td class="text-center">R$ <?= $coupon->valor ?></td>
                            <td class="text-center"><?= $coupon->store ?></td>
                            <td class="text-center"><?= $coupon->date_time ?></td>
                            <td class="text-center"><?= $coupon->status ? 'Ativo' : 'Inativo' ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="row">
                    <div class="col">
                        <p class="mb-5">Total: <strong><?=count($my_coupons)?></strong></p>
                    </div>
  <!--                   <div class="col text-end">
                        <a href="#" class="btn btn-secondary">
                            <i class="fa-regular fa-file-excel me-2"></i>Exportar para XLSX
                        </a>
                    </div> -->
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>