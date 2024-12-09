<style>
    .rowcat .col-md-6 {
        border: 1px solid #c1c1c1;
    }

    .rowcat .control-label {
        text-align: right;
    }

    .rowcat1 .row {
        border: 1px solid #c1c1c1;
        padding: 7px;
    }
</style>
<div class="container-fluid">
    <div class="col-md-12">
        <div class="modal-body">
            <?= $this->Form->create($proposal, ['name' => 'proposal', 'id' => 'proposal'], array('action' => 'projects/proposal'));
            echo $this->Form->input('project_id', ["type" => "hidden", "value" => (!empty($this->request->params['pass'][0]) ? decode($this->request->params['pass'][0]) : '')]); ?>
            <div class="form-group">
                <label for="pv_wp" class="control-label col-sm-2">Technical</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->checkbox('technical'); ?>
                </div>
                <label for="pv_wp" class="control-label col-sm-2">Commercial</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->checkbox('commercial1'); ?>
                </div>
            </div>

            <div class="form-group">
                <label for="pv_wp" class="control-label col-sm-2">Email Customer</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->checkbox('email_customer'); ?>
                </div>
                <label for="pv_wp" class="control-label col-sm-2">Email My Team</label>
                <div class="col-sm-2">
                    <?php echo $this->Form->checkbox('email_team'); ?>
                </div>
            </div>
            <div class="row">
                <div class="_100 h_63">
                    <p>
                        <label class="spanright">
                            <span><a href="javascript:;" class="addmore2">+Add Email</a></span>
                        </label>
                    </p>
                </div>
            </div>
            <?php
            $email_arr   = array();
            $em_det           = '0';
            if(isset($proposal) && !empty($proposal['email']))
            {
                $em_details     = unserialize($proposal['email']);
                $email_arr = $em_details;
            }
            ?>
            <table id="customFields2" width="100%">
                <tbody>
                <?php
                if(empty($email_arr))
                {
                    ?>
                    <tr id="rowId2">
                        <td class="wwe-lang-matches" scope="col">
                            <div class="form-group" id="eid" style="display: none;">
                                <label class="control-label col-sm-2">Email Id</label>
                                <div class="col-sm-3">
                                    <?php echo $this->Form->input('email[]', ["type" => "text", "class" => "form-control", 'label' => false]); ?>
                                </div>
                                <div class="col-sm-1">
                                    <p width="5%" class="remove_button"><i class="fa fa-remove" style="color:#ff0000;"></i>
                                    </p>
                                </div>
                            </div>

                        </td>
                    </tr>
                    <?php
                }
                foreach($email_arr as $key=>$em_det)
                {
                    if($em_det != "") {
                        $tr_id = '';
                        if ($key !== 0 ) {
                            $tr_id = "rowId2";
                        }
                        ?>
                        <tr id="<?php echo $tr_id; ?>">
                            <td class="wwe-lang-matches" scope="col">
                                <div class="form-group" id="eid" style="">
                                    <label class="control-label col-sm-2">Email Id</label>
                                    <div class="col-sm-3">
                                        <?php echo $this->Form->input('email[]', ["type" => "text", "class" => "form-control", 'label' => false, "value" => $em_det]); ?>
                                    </div>
                                    <div class="col-sm-1">
                                        <p width="5%" class="remove_button"><i class="fa fa-remove"
                                                                               style="color:#ff0000;"></i>
                                        </p>
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <?= $this->Form->button(__('Submit'), ['type' => 'submit', 'id' => 'save_note', 'class' => 'btn-primary btn pull-left']); ?>

            <?= $this->Form->end(); ?>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var selectid2 = 1;
        var clonerow = '';
        if ($("#rowId2").attr('id') == 'rowId2') {
            clonerow2 = $("#rowId2").clone();
        }

        $('.addmore2').click(function () {

            clonerow2 = $(clonerow2).removeAttr("id");
            $(clonerow2).find("a").removeClass('hide');
            $(clonerow2).find("input").val('');
            $(clonerow2).find("input").removeAttr("id");
            $(clonerow2).find("select").attr("id", $(clonerow2).find("select").attr("id") + selectid2);
            $(clonerow2).find("#" + $(clonerow2).find("select").attr("id") + "_chzn").remove();
            $(clonerow2).find("select").removeClass('chzn-done');
            $(clonerow2).find("#eid").removeAttr("style");
            $(clonerow2).find("#" + $(clonerow2).find("select").attr("id") + selectid2).removeAttr('style');
            // $(clonerow2).find(".fa-remove").removeAttr("style");
            // $(clonerow2).find(".fa-remove").attr("style",'color:#ff0000;cursor:pointer;');
            $(clonerow2).find(".fa-remove").attr("onclick", 'javascript:$(this).parent().parent().parent().remove();');
            $('#customFields2 tr').last().after($(clonerow2).clone());
            $('#customFields2 tbody tr').last().find('span.checkbox').remove();
            $('#customFields2 tbody tr').last().find("input[type='checkbox']").attr('checked', false);
            $('#customFields2 tbody tr').last().find('input[type=checkbox]').each(function () {
                $(this).checkbox({
                    cls: 'checkbox',

                    empty: WEB_ADMIN_URL + 'img/sprites/forms/checkboxes/empty.png'
                });
            });
            selectid2++;
        });

    });

    $(document).on('click', '#addNote', function () {

        $('#add_projects_note_model').modal('show');
    });

    $("#add_project_note_form").submit(function (e) {
        var form_data = new FormData(this);
        jQuery.ajax({
            url: '<?php echo URL_HTTP . "projects/saveProjectNote"; ?>',
            type: 'POST',
            data: form_data,
            dataType: 'json',
            mimeType: "multipart/form-data",
            processData: false,
            contentType: false,
            success: function (results) {
                if (results.status == '1') {
                    location.reload();
                } else {

                }
            }
        });
        e.preventDefault();
    });
</script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(monthDrawChart);

    function monthDrawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Energy', 'Month'],
            <?php echo $monthChart; ?>
        ]);
        var view = new google.visualization.DataView(data);
        var options = {
            title: "Month Energy Chart",
            width: '100%',
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("month_data_chart"));
        chart.draw(view, options);
    }

    google.charts.setOnLoadCallback(yearDrawChart);

    function yearDrawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Energy', 'Year'],
            <?php echo $yearChart; ?>
        ]);
        var view = new google.visualization.DataView(data);
        var options = {
            title: "Year Energy Chart",
            width: '100%',
            height: 400,
            bar: {groupWidth: "60%"},
            colors: ['#FFCB29'],
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("year_data_chart"));
        chart.draw(view, options);
    }

</script>