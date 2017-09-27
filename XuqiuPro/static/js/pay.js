$(document).ready(function(){
	var coininfo = {};//计算元宝的相应信息，coinname:游戏币名称，gamerate:游戏兑换比例，moneyrate:支付比例,money:支付金额
	$(".gl,.sl").hide();
	$(".bodyleftbottom .tr1").click(function(){
		$('#step2,#step3').hide();
		$('#step1').show();
		$(".tr1 .td2 .current3").removeClass('current3');
		$(this).find('span').addClass('current3');
		$("input[name='code']").val($(this).attr('code'));
		$("input[name='pay_id']").val($(this).attr('pay_id'));
		var pay_cat = $(this).attr('pay_cat');
		coininfo.moneyrate = $(this).attr('trade_rat');
		switch(pay_cat){
			case "1":
				//银行卡（易宝）
				$(".platcoin2").hide();
				$(".gamelabel").trigger('click');
				$(".cardmoney").hide();
				$(".normalmoney").show();
				$(".banktl").show();
				$(".bank").show();
				$(".banka").eq(0).trigger('click');
				$(".platinput,.platlabel").show();
				$.countcoin();
				break;
			case "2":
				//卡类支付
				$(".normalmoney").hide();
				$(".banktl").hide();
				$(".bank").hide();
				$(".platcoin2").hide();
				$(".gamelabel").trigger('click');
				$('.cardmoney').show();
				$(".platinput,.platlabel").show();
				$.countcoin();
				break;
			case "3":
				//平台币支付
				$(".cardmoney").hide();
				$(".banktl").hide();
				$(".bank").hide();
				$(".platinput,.platlabel").hide();
				$(".gamelabel").trigger('click');
				$(".platcoin2").show();
				$(".normalmoney").show();
				$.countcoin();
				break;
			case "4":
				//支付宝
				$(".platcoin2").hide();
				$(".gamelabel").trigger('click');
				$(".cardmoney").hide();
				$(".banktl").hide();
				$(".bank").hide();
				$(".normalmoney").show();
				$(".platinput,.platlabel").show();
				$.countcoin();
				break;
		}
	});
	$("input[name='pay_for']").next().click(function(){
		//选择充值到哪里
		$(this).prev().attr('checked',1);
		var payfor = $(this).attr('class');
		switch(payfor){
			case 'gamelabel':
				$(".platcoin").hide();
				$(".gamesel").show();
				$("input[name='pay_for']").val('game');
				break;
			case 'platlabel':
				$(".platcoin").show();
				$(".gamesel").hide();
				$(".gamelist").hide();
				$(".serverlist").hide();
				$("input[name='pay_for']").val('platform');
				$("input[name='gid']").val(0);
				$("input[name='sid']").val(0);
				coininfo.coinname = "平台币";
				coininfo.gamerate = 1;
				$.countcoin();
				break;
			default:
				break;
		}
	});
	$("input[name='pay_for']").click(function(){
		//选择充值方式单选按钮
		$(this).next().trigger('click');
	});
	$(".selectgame").click(function(){
		//显示游戏列表
		$(".serverlist").hide();
		$(".gamelist .char span").eq(0).trigger('click');
		$(".gamelist").show();
	});
	$(".char").find("span").click(function(){
	//单击不同的游戏列表显示不同的内容
		$(".char").find("span").each(function(index){
			//所有游戏指针背景清除；
			$(this).css('background','none');
		});
		var spanid = $(this).index();
		$(this).css('background','white');
		var actfor = $(this).parent().attr('actfor');
		if(actfor == 'gl'){
			$(".gl").each(function(index){
				//循环游戏列表，对序号相同的进行显示操作
				$(this).hide();
				if(index == spanid){
					$(this).show();
				}
			});
		}
		if(actfor == 'sl'){
			$(".sl").each(function(index){
				//循环服务器列表，对序号相同的进行显示操作
				$(this).hide();
				if(index == spanid){
				$(this).show();
				}
			});
		}
		
	});
	$(document).on('mouseover',".gl label,.sl label,.moneyblock span",function(){
		//添加下划线
		$(this).css('text-decoration','underline');
		$(this).css('color','#f6611f');
	});
	$(document).on('mouseleave',".gl label,.sl label,.moneyblock span",function(){
		//删除下划线
		$(this).css('color','#6a6a6a');
		$(this).css('text-decoration','none');
	});
	$(document).on('click','.sl label',function(){
		//点击服务器后，在对应位置显示
		$(".selectserver span").html($(this).html());
		$(".serverlist").hide();
		$("input[name='sid']").val($(this).attr('sid'));
		$(this).prev().attr('checked',1);
		var url = "/index.php?m=pay&p=checkuser";
		var sid = $(this).attr('sid');
		var game_code = $(this).attr('game_code');
		var username = $("input[name='username']").val();
		$.post(url,"username="+username+"&sid="+sid+"&game_code="+game_code,function(ret){
			if(!ret.role_name){
				alert('无法找到对应角色！');
				$(".selectserver").html("<span>请选择游戏服务器</span>");
				$("input[name='sid']").val("");
			}else{
				$("#character_name").text(ret.role_name);
			}
		},"json");
		
	});
	$(document).on('click','.moneyblock input,.sl input,.gl input',function(){
		//点击单选按钮，右侧span模拟点击
		$(this).next().trigger('click');
	})
	$(".char .close").click(function(){
		//隐藏游戏列表
		$(".gamelist").hide();
		$(".serverlist").hide();
	});
	$(".gl label").click(function(){
		//点击游戏后，在对应位置显示并显示服务器列表
		$(".selectgame span").html($(this).html());
		$(".gamelist").hide();
		$.showselect($(this).attr('gid'));
		coininfo.gamerate = $(this).attr('gamerate');
		coininfo.coinname = $(this).attr('coinname');
		$.countcoin();
		$("input[name='gid']").val($(this).attr('gid'));
		//clickorhover = 1;
	});
	$(".selectserver").click(function(){
		//显示服务器列表
		$(".gamelist").hide();
		$(".serverlist .char span").eq(0).trigger('click');
		$(".serverlist").show();
	});

	$(".fr a.confirm").click(function(){
		//输入选服，点击确定
		var selectsid = $(this).prev().find("input").val();
		$(".sl label").each(function(index){
			//循环服务器列表，找到对应的服务器进行模拟点击，否则弹出警告信息
			if($(this).attr('quick') == selectsid){
				$(this).trigger('click');
				return false;
			}
		});
		if($(this).is(":visible")){
			alert('不存在该服务器');
		}
	});
	$(".moneyblock span").click(function(){
		//点击充值金额，显示对应元宝
		//clickorhover = 1;
		var money1 = $(this).html();
		$(this).prev().attr('checked',1);
		money1 = money1.substr(0,money1.length - 1);
		$("input[name='pay_money']").val(money1);
		coininfo.money = money1;
		$.countcoin();
	});
	$(".brtr2-tr3 .othermoney").click(function(){
		//点击其他金额，右边的单选框为选中状态
		$(this).prev().prev().attr('checked','checked');
		$(this).prev().prev().trigger('click');
	}).blur(function(){
		//失去焦点后在对应位置显示元宝
		$(this).prev().prev().attr('checked','checked');
		$(this).prev().prev().trigger('click');
		var money2 = $(this).val();
		if(!isNaN(money2)){
			$("input[name='pay_money']").val(money2);
			coininfo.money = money2;
			//console.log(coininfo);
			$.countcoin();
		}else{
			alert('请输入正确的金额！');
		}
	});
	$("input[name='tradepassword']").blur(function(){
		$("input[name='trade_password']").val($(this).val());
	});
	$(".banka").click(function(){
		//点击银行
		var inputid = $(this).attr('forradio');
		$("input[name='bankid']").val($("#" + inputid).val());
		$("#" + inputid).trigger('click');
	});
	$("input[name='bankid1']").click(function(){
		$("input[name='bankid']").val($(this).val());
	});
	$(".JUN").click(function(){
		$("input[name='bankid']").val('JUNNET-NET');
	});
	$(".SHEN").click(function(){
		$("input[name='bankid']").val('SZX-NET');
	});
	$(".LIAN").click(function(){
		$("input[name='bankid']").val('UNICOM-NET');
	});
	$(".DIAN").click(function(){
		$("input[name='bankid']").val('TELECOM-NET');
	});
	$(".ZHENG").click(function(){
		$("input[name='bankid']").val('ZHENGTU-NET');
	});
	$(".SHENG").click(function(){
		$("input[name='bankid']").val('SNDACARD-NET');
	});
	$(".WANG").click(function(){
		$("input[name='bankid']").val('NETEASE-NET');
	});
	$(".TIAN").click(function(){
		$("input[name='bankid']").val('TIANXIA-NET');
	});



	$.showselect = function(gid){
		//通过gid获取最近玩过的服务器，并显示服务器列表
		$(".selectserver").trigger('click');
		var url = "/index.php?m=pay&p=getservers";
		$.ajax({
			type:"POST",
			url:url,
			async:false,
			data:{gid:gid},
			success:function(data){
				//console.log(data);
				var obj = jQuery.parseJSON(data);
				var len = obj.length;
				if(len <= 100){
					$(".serverlist .sl").eq(1).html("");
					$(".serverlist .char span").eq(1).show();
					for(var i = 0;i < len;i++){
						var name = obj[i]['name'];
						var arr = name.match(/*(\d{1,3})*/i);
						if(arr){var quick = arr[0];}else{quick = 0;}
						var temphtml = "<input type='radio' name='slist' value='" + obj[i]['sid'] + "' game_code='"+obj[i]['game_code']+"'/>&nbsp;<label sid='" + obj[i]['sid'] + "' game_code='"+obj[i]['game_code']+"' quick='" + quick + "'>" + obj[i]['name'] + "</label>";
						
						$(".serverlist .sl").eq(1).append(temphtml);
					}
				}
			}
		});
	};
	$.countcoin = function(){
		//计算获取元宝数量，并显示在对应位置
		if(!coininfo.coinname || !coininfo.gamerate || !coininfo.moneyrate || !coininfo.money){
			//参数不全，不显示元宝数量
			$(".emoney").css({"visibility":"hidden"});
		}else{
			if($(".moneyblock input:checked").is(":visible")){}else{
				if($(".othermoney").is(":visible")){}else{
					coininfo.money = null;
					$(".emoney").css({"visibility":"hidden"});
					$(".default:visible").find("span").trigger('click');
					return false;
				}
			}
			var temphtml = "对应" + coininfo.coinname + "数量：" + (coininfo.money * coininfo.gamerate * coininfo.moneyrate) + "<font color='#f6611f'>[兑换比例为1:" + (coininfo.gamerate * coininfo.moneyrate) + "]</font>";
			$(".emoney").html(temphtml);
			$(".emoney").css({"visibility":"visible"});
			$("input[name='game_money']").val(coininfo.money * coininfo.gamerate * coininfo.moneyrate);
		}
	}

	$(".bodyleftbottom .tr1").eq(0).trigger('click');

	$(".page1submit").on('click',function(){
		if($("input[name='pay_for']").val() == 'game'){
			if($("input[name='gid']").val() == ''){
				alert("请选择要充值的游戏！");
				return false;
			}
			if($("input[name='sid']").val() == ''){
				alert("请选择要充值的服务器！");
				return false;
			}
		}else{
			/*if($("input[name='pay_for']").val() == 'platform'){
				alert("暂不支持充值到平台！");
				return false;
			}*/
		}
		if($("input[name='game_money']").val() == ''){
			alert("请选择要充值的金额！");
			return false;
		}
		if($("input[name='pay_money']").val() < 10){
            if($("input[name='username']").attr('white') == 0){
                alert("充值金额不能低于10元！");
                return false;
            }
		}
		if($("input[name='code']").val() == 'platform'){
			if($("input[name='trade_password']").val() == ''){
				alert("请输入交易密码！");
				return false;
			}
		}
		var param = $("form[name='form2']").serialize();
		var url = "/index.php?m=pay&p=confirm";
		$.post(url,param,function(ret){
                if(ret.code == 1){
                    //成功返回，进入第二步
                    $("#step1").hide();
                    $(".con-orderid").html(ret.msg.order_no);
                    $('.con-username').html(ret.msg.user.username);
                    $('.con-money').html(ret.msg.money);
                    var payname = "";
                    switch (ret.msg.confirm.method){
                        case 'alipay':
                            payname = '支付宝';
                            break;
                        case 'yeepay':
                        	payname = ret.msg.confirm.bankname;
                        	break;
                        case 'platform':
                        	payname = '平台币支付';
                        	break;
                    }
                    $('.con-paymethod').html(payname);
                    $('.bottombtn form').remove();
                    $('.bottombtn').append(ret.msg.confirm.forminfo);
                    $("#step2").show();
                }else{
                    alert(ret.msg);
                }
		},"json");
	});

	$('.gl-v').each(function(index){
		//查找最新玩的游戏ID
		if($(this).find('label').attr('gid') == last_game_id){
			$(this).clone(true).appendTo('.gamelist .newplay');
			$(this).find('label').trigger('click');
		}
	});
	$(".sl input[name='slist']").each(function(index){
		//查找最新玩的服务器ID
		if($(this).val() == last_server_id){
			$(this).clone(true).appendTo('.serverlist .newplay');
			$('.serverlist .newplay').append('&nbsp;');
			$(this).next().clone(true).appendTo('.serverlist .newplay');
			$(this).trigger('click');
			//alert($(this).next().html());
		}
	})

	//$.countcoin();

    /*step2*/
    $(".page2submit").click(function(){
        //点击“马上充值”
        $(this).next().next().submit();
    });
    $(".page2return").click(function(){
        //点击“我要修改”
        $("#step2").hide();
        $('#step3').hide();
        $("#step1").show();
    });


    /*step3*/

    if(step3){
    	$('#step3 .brtr2-1-1-h').html(step3info.msg);
    	$('.three-orderid').html(step3info.orderid);
    	$('.three-money').html(step3info.pay_money);
    	$('.three-time').html(step3info.time);
		$("#step1").hide();
		$('#step2').hide();
		$('#step3').show();
	}

	$('.page3submit').click(function(){
		//点击“继续充值”
		window.location.href = "index.php?m=pay&p=index";
	});
	$('.page3return').click(function(){
		//点击“返回用户中心”
		window.location.href = "index.php?m=user&p=my_info";
	})

})