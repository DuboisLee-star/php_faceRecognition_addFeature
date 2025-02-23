<?php if(!isset($app)) exit(); 
require_once('config.php');
?>

            <?php if(ACCESS_TOKEN == '' || PUBLIC_KEY == ''): ?>
                <?php if(ACCESS_TOKEN == ''): ?><div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <b>ACCESS_TOKEN</b> não configurado.</div><?php endif; ?>
                <?php if(PUBLIC_KEY == ''): ?><div class="alert alert-warning" role="alert"><i class="fa fa-info-circle" aria-hidden="true"></i> <b>PUBLIC_KEY</b> não configurado.</div><?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fa fa-info-circle" aria-hidden="true"></i> Por favor, selecione a forma de pagamento abaixo.
                </div>
            <?php endif; ?>

            <div class="row">

                <?php if(ACCESS_TOKEN != '' || PUBLIC_KEY != ''): ?>
                
                    <!-- pix -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?= URL_SITE; ?>/pagamento/image/pix.jpg" class="card-img-top card-header" alt="PIX">
                            <div class="card-body">
                                <h5 class="card-title text-center">PIX</h5>
                            </div>
                            <div class="card-footer text-center">
                                <div class="d-grid gap-2">
                                    <?php if($valor_plano > 0): ?>
                                        <button class="btn btn-primary btn-block" type="button" onclick="selecionaPagamento('pix')">Pagar com PIX</button>
                                    <?php else: ?>
                                        <div class="alert alert-warning" role="alert">
                                            Valor mínimo é de R$ 0,01
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- cartão de crédito -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?= URL_SITE; ?>/pagamento/image/cartao.jpg" class="card-img-top card-header" alt="Cartão de Crédito">
                            <div class="card-body">
                                <h5 class="card-title text-center">Cartão de Crédito</h5>
                            </div>
                            <div class="card-footer text-center">
                                <div class="d-grid gap-2">
                                    <?php if($valor_plano > 5): ?>
                                        <button class="btn btn-primary btn-block" type="button" onclick="selecionaPagamento('cartao')">Pagar com Cartão</button>
                                    <?php else: ?>
                                        <div class="alert alert-warning" role="alert">
                                            Valor mínimo é de R$ 5,00
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- boleto -->
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <img src="<?= URL_SITE; ?>/pagamento/image/boleto.jpg" class="card-img-top card-header" alt="Boleto Bancário">
                            <div class="card-body">
                                <h5 class="card-title text-center">Boleto Bancário</h5>
                            </div>
                            <div class="card-footer text-center">
                                <div class="d-grid gap-2">
                                    <?php if($valor_plano > 10): ?>
                                        <button class="btn btn-primary btn-block" type="button" onclick="selecionaPagamento('boleto')">Gerar Boleto</button>
                                    <?php else: ?>
                                        <div class="alert alert-warning" role="alert">
                                            Valor mínimo é de R$ 10,00
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
