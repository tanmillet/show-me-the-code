define(['jquery'], function ($) {

    function AjaxModel() {
    }

    AjaxModel.prototype = {
        loseAjax: function (url, method, params, dataType, callback, btName)
        {
            $.ajax({
                url: url, type: method, data: params, dataType: dataType, success: callback,
                error: function () {
                    var dialogInstance = new BootstrapDialog();
                    dialogInstance.setType(BootstrapDialog.TYPE_DANGER)
                        .setTitle('运行结果').setMessage("网络异常").open();
                    $("+btName+").removeAttr("disabled");
                }
            })
        },
    }

    return {
        AjaxModel: AjaxModel
    }

});