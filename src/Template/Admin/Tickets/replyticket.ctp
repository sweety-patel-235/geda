<?php
/**
 * Admin Ticket Details.  Displays Ticket Details
 * @package      app.View.Elements
 * @author       jaysinh Rajpoot
 * @since        01/04/2015
 */
?>
<div class="grid_12">
    <div class="box">
        <div class="header2">
            <h3>Ticket Details</h3>
        </div>
        <div class="content">
        <?php
            echo $this->Form->create('Ticket',array('id'=>'formmain'));
            echo $this->Form->hidden('Ticket.parent_id',array('value'=>$data[0]['Ticket']['parent_id']));
        ?>  <table border="0" width="100%" align="center" cellpadding="0" cellspacing="0"  id="table-example" class="table">
                <tbody>
                    <tr>
                        <td class="sorting">
                            <b>Subject</b>
                        </td>
                        <td>
                            <?php echo $data[0]['Ticket']['subject'];?>
                            <span class="spanright"><?php echo date('d-M-Y h:i:s A',strtotime($data[0]['Ticket']['created']));?></span>    
                        </td>               
                    </tr>
                    <tr>
                        <td>
                            <b>Message</b>
                        </td>
                        <td>
                            <?php echo $data[0]['Ticket']['message'];?>    
                        </td>                      
                    </tr>
                     <tr>
                        <td>
                            <b>Send to</b>
                        </td>
                        <td>
                            <?php 
                            if(isset($type) && !empty($type)){
                                if($type=='reply'){
                                    echo ucfirst($data[0]['User']['from']);
                                    echo $this->Form->hidden('Ticket.to_id',array('value'=>$data['0']['Ticket']['from_id']));
                                }
                                elseif($type=='send'){
                                    echo $this->Form->input('Ticket.to_id',array('type'=>'select','options'=>$users,'empty'=>'-select user-','div'=>false,'label'=>false,'required'));
                                }
                            }
                            ?>    
                        </td>                      
                    </tr>
                    <tr>
                        <td valign="top">
                            <b class="">Reply</b>
                        </td>
                        <td valign="top">
                            <?php echo $this->Form->input('Ticket.message',array('type'=>'textarea','required','div'=>false,'label'=>false ,'rows'=>2));?>
                        </td>    
                    </tr>
                    <tr>
                        <td colspan="2">
                            <p class="mybutton1">
                                <button type="submit" class="positive mybuttonleft" id="searchbtn" tabindex="5">
                                    Submit
                                </button>
                                <button type="button" onclick="javascript:parent.jQuery.fancybox.close();">
                                    Close
                                </button>
                            </p>
                        </td>      
                    </tr>
                </tbody>    
            </table>
            <?php echo $this->Form->end();?>
        </div> <!-- End of .content -->
        <div class="clear"></div>
    </div> <!-- End of .box -->
</div> <!-- End of .grid_12 -->





