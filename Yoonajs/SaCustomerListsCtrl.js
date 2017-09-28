require.config({
    baseUrl: '/Lose/',
    paths: {
        'jquery': 'lib/jquery.min',
        'AjaxModel': 'app/AjaxModel',
        'layer': 'front/mobile/layer',
    }
});

require(['jquery', "AjaxModel",'layer'], function ($, req,layer) {
    if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
        var clickName = 'touchend';
    } else if (/(Android)/i.test(navigator.userAgent)) {
        var clickName = 'click';
    } else {
        var clickName = 'click';
    }
    $(function () {

        var request = new req.AjaxModel();

        function ajaxRequest(objPrm, type) {
            var linkTo = '/lostcustomer/lists123';
            $.ajax({
                type: "POST",
                headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                url: objPrm.link,
                data: objPrm.data,
                dataType: "json",
                success: function (resp) {
                    if (resp.success) {
                        if (type == 'mark_type') {
                            layer.open({
                                content: '标签更新成功！该客户名单是否退回给上级吗？'
                                , btn: ['确定', '取消']
                                , skin: 'footer'
                                , yes: function (index) {
                                    var passData = {
                                        link: '/lostcustomer/returntodsm',
                                        data: {"customerid": resp.cid}
                                    };
                                    ajaxRequest(passData, '');
                                },
                                no: function (index) {
                                    layer.closeAll();
                                    window.location.href = linkTo;
                                }
                            });
                        } else {
                            layer.open({
                                content: '标签更新成功！'
                                , skin: 'footer'
                                , btn: ['刷新']
                                , yes: function (index) {
                                    window.location.href = linkTo;
                                }
                            });
                        }
                    } else {
                        layer.open({
                            content: '操作失败，请重试！可能原因：' + resp.msg
                            , skin: 'footer'
                            , btn: ['刷新']
                            , yes: function (index) {
                                window.location.href = linkTo;
                            }
                        });

                    }
                },
                error: function (xhr, textStatus) {
                    layer.open({
                        content: '网络异常，请重试！'
                        , skin: 'footer'
                        , btn: ['刷新']
                        , yes: function (index) {
                            window.location.href = linkTo;
                        }
                    });
                }
            });
        }

        // 拨打电话
        $(document).on(clickName, 'a.telephone', function () {
            var whois = $(this).attr('extra-cname');
            var phone = $(this).attr('extra-cphone');
            if (whois == '' || phone == '') {
                layer.open({
                    content: '电话号码不存在！'
                    , skin: 'footer'
                    , btn: ['关闭']
                });
            } else {
                //询问框
                layer.open({
                    content: '拨打电话确认客户意向！'
                    ,btn: ['拨打', '取消']
                    ,yes: function(index){
                        $.ajax({
                            type: "POST",
                            headers: {"X-CSRF-TOKEN": "{{csrf_token()}}"},
                            url: '/lostcustomer/phone',
                            data: {},
                            dataType: "json",
                            success: function (resp) {
                            },
                            error: function (xhr, textStatus) {
                            }
                        });
                        window.location.href = 'tel:' + phone;
                    }
                });
            }
        });




        // "标记"按钮点击事件
        $(document).on(clickName, 'a.action-mark', function () {
            var cid = $(this).attr('extra-cid');
            layer.open({
                type: 1,
                title: [
                    '请选择标记类型',
                    'background-color: #d6cdce; color:#060606;'
                ],
                content: '<div style="width: 240px;">' +
                '<input type="hidden" id="custid" extra-cid="' + cid + '">' +
                '<div style="margin-top: 15px; margin-left: 15px;"><input type="radio" name="radio-choice" id="radio-choice-1" value="1" style="float:left;"/>' +
                '<label for="radio-choice-1">联系不上</label></div>' +
                '<div style="margin-top: 15px; margin-left: 15px;"><input type="radio" name="radio-choice" id="radio-choice-2" value="2" style="float:left;"/>' +
                '<label for="radio-choice-2">需要考虑</label></div>' +
                '<div style="margin-top: 15px; margin-left: 15px;"><input type="radio" name="radio-choice" id="radio-choice-3" value="3" style="float:left;"/>' +
                '<label for="radio-choice-3">没兴趣</label></div>' +
                '<div style="margin-top: 15px; margin-left: 15px;"><input type="radio" name="radio-choice" id="radio-choice-4" value="4" style="float:left;"/>' +
                '<label for="radio-choice-4">客户有风险</label></div>' +
                '<div style="margin-top: 15px; margin-left: 15px;"><input type="radio" name="radio-choice" id="radio-choice-5" value="returnBack" style="float:left;"/>' +
                '<label for="radio-choice-5">名单退回给上级</label></div>' +
                '<div style="margin-top: 5px;">&nbsp;</div>' +
                '</div>'
            })
        });


        // 弹出层标记类型选择事件
        $(document).on('change', 'input[name="radio-choice"]', function () {
            var choice = $(this).val();
            var cid = $('#custid').attr('extra-cid');
            layer.closeAll();
            if (choice === 'returnBack') {
                layer.open({
                    content: '确定将客户名单退回给上级？'
                    , btn: ['确定', '取消']
                    , skin: 'footer'
                    , yes: function (index) {
                        var passData = {
                            link: '/lostcustomer/returntodsm',
                            data: {"customerid": cid}
                        };
                        ajaxRequest(passData, '');
                    },
                    no: function (index) {
                        layer.closeAll();
                    }
                });
            } else {
                var passData = {
                    link: '/lostcustomer/operate',
                    data: {
                        "mark_type": choice,
                        "customerid": cid
                    }
                };
                var mark_type = (choice == 2) ? '' : 'mark_type';
                ajaxRequest(passData, mark_type);
            }
        });


        // 获取更多
        $(document).on(clickName, 'a.moreA', function () {
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{csrf_token()}}'},
                url: '/lostcustomer/sapplymore',
                data: {},
                dataType: 'json',
                success: function (resp) {
                    if (resp.success) {

                        layer.open({
                            content: '已经向上级发送了申请更多名单的消息！'
                            , skin: 'footer'
                        });
                    } else {
                        layer.open({
                            content: '网络异常，请重试！'
                            , skin: 'footer'
                        });
                    }
                },
                error: function (xhr, textStatus) {
                    layer.open({
                        content: '网络异常，请重试！'
                        , skin: 'footer'
                    });
                }
            });
        })
    });

});