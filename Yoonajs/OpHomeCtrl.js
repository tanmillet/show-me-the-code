require.config({
    baseUrl: '/Lose/',
    paths: {
        'jquery': 'lib/jquery.min',
        'bootstrap': 'lib/bootstrap',
        'bootstrap-dialog': 'lib/bootstrap-dialog.min',
        'bootstrap-datetimepicker' : 'lib/bootstrap-datetimepicker',
        'bootstrap-datetimepicker.zh-CN' : 'lib/bootstrap-datetimepicker.zh-CN',
        'AjaxModel': 'app/AjaxModel',
    }
});

require(['jquery', "AjaxModel", 'bootstrap-dialog','bootstrap-datetimepicker','bootstrap-datetimepicker.zh-CN'], function ($, req, bootdialog) {

    $('.form_date').datetimepicker({
        language: 'zh-CN',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });

    $(".a-doc").click(function () {
        bootdialog.show({
            title: '数据流程图',
            message: '<img src="/btstrap/images/flow.png" alt="数据流程" width="500px">'
        });
    });

    $(".btn-ophome").click(function () {
        var me = this,
            formId = me.id
        formData = $("#" + formId + "-form").serialize(),
            formUrl = $(me).attr('data-url');
        $(me).attr('disabled', "true");

        var request = new req.AjaxModel();

        request.loseAjax(formUrl, 'get', formData, 'json', function (res) {
            bootdialog.show({
                title: '运行结果',
                message: res.msg
            });
            $(me).removeAttr("disabled");
        }, 'btn-ophome');
    });

});