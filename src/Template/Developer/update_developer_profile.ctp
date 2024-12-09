<?php
$this->Html->addCrumb($pageTitle);
?>
<!-- src/Template/Users/add.ctp -->
<div class="container">
    <div class="row ">
        <div class="col-sm-6" style="margin-bottom: 10px;">
            <div class="users form">
                <?= $this->Form->create($user, ['class' => 'validate-form', 'method' => 'post', 'type' => 'post']) ?>
                <fieldset>
                    <label>Email : <?php echo $user->email; ?></label>
                    <?= $this->Form->input('name', ['class' => 'required']) ?>
                    <?= $this->Form->input('mobile') ?>

                </fieldset>
                <?= $this->Form->button(__('Submit')); ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <?= $this->Form->create($user, ['class' => 'validate-form', 'method' => 'post', 'type' => 'post', 'id' => 'form_calc', 'action' => "/update_developer_package"]) ?>
                <div class="panel-heading" style="background-color:#4CC972;color:#fff">
                    <h4 style="color:#fff">Category Details</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <?php
                        if (!empty($activeCategoryIds)) {
                            $displayButton = 0;
                            foreach ($activeCategoryIds as $categoryId) {
                                if (in_array($categoryId, $developerCategory)) { ?>
                                    <div class="col-sm-3">
                                        <div class="well" style="height:180px;text-align: center;">
                                            <h5><?= $activeCategory[$categoryId]['category_name'] ?></h5>
                                            <p style="font-size: 14px;font-weight: bolder"><?= $activeCategory[$categoryId]['developer_charges'] ?> <br>+<br> <?= $activeCategory[$categoryId]['developer_tax_percentage'] ?>% Tax</p>
                                        </div>
                                    </div>
                                <?php
                                } else {
                                    $displayButton=1; 
                                ?>
                                    <div class="col-sm-3">
                                        <div class="well" style="height:180px;text-align: center;">
                                            <?php $val = $activeCategory[$categoryId]['developer_charges'] + ($activeCategory[$categoryId]['developer_charges'] * $activeCategory[$categoryId]['developer_tax_percentage'] / 100); ?>
                                            <input type="checkbox" name="category[]" style="accent-color: #4CC972;" value="<?= $categoryId ?>" data-item-price=<?= $val ?>>
                                            <h5><?= $activeCategory[$categoryId]['category_name'] ?></h5>
                                            <p style="font-size: 14px;font-weight: bolder;color"><?= $activeCategory[$categoryId]['developer_charges'] ?> <br>+<br> <?= $activeCategory[$categoryId]['developer_tax_percentage'] ?>% Tax</p>

                                        </div>
                                    </div>
                        <?php
                                }
                            }
                        }
                        ?>

                    </div>
                    <?php echo $this->Flash->render('alert'); ?>
                </div>
                <?php if($displayButton==1){ ?>
                <div class="panel-footer  ">
                    <span id="price" class="text-left" style="font-weight:bolder">Total Processing Fees including GST (in ₹) <span style="color:#4CC972;font-size:18px"> 0</span></span>
                    <button type="submit" id="update" class="btn btn-success pull-right disable" style="background-color: #4CC972"> Upgrade Category</button><br>&nbsp;
                </div>
                <?php } ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- <div class="col-sm-6"></div> -->
        <div class="col-sm-12 col-md-6 pull-right">
            <h5>Download Receipt</h5>
            <ul class="list-group" >
                <?php 
                if(isset($PaymentReceiptDetails) && !empty($PaymentReceiptDetails)){
                    foreach($PaymentReceiptDetails as $v)
                    {
                ?>
                        <li class="col-sm-12 list-group-item" style="display:inline-block">
                            <div class="col-sm-4"><?php echo $v['receipt_no']?></div>
                            <div class="col-sm-4"><?php echo $v['created']?></div>
                            <div class="col-sm-4 text-right">
                                
                                <?php echo '<a href="/download-developer-payment-receipt/'. encode($v->id) .'" target="_blank">
												<i class="fa fa-download"></i> Download Receipt
											</a>'; ?>
                            </div>
                            
                        </li>
                <?php
                    }
                }
                ?>
            </ul>
            
        </div>
    </div>
</div>
    <script>
        jQuery(document).ready(function($) {

            //$('#update').prop('disabled', true);
            $("#form_calc").change(function() {
                var totalPrice = 0,
                    values = [];
                $('input[type=checkbox]').each(function() {
                    if ($(this).is(':checked')) {
                        values.push($(this).attr("data-item-price"));
                        totalPrice += parseInt($(this).attr("data-item-price"));
                        $('#update').prop('disabled', false);
                    }
                });
                $("#price span").text(totalPrice);
                // if(totalPrice==0)
                //     $('#update').prop('disabled', true);           
            });

        });
    </script>