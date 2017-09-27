<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>
<body>
省：<select name="province" id="province" style="width: 150px;height: 25px;border-radius: 5px;"></select><br>
市：<select name="city" id="city" style="width: 150px;height: 25px;border-radius: 5px;"></select><br>
县：<select name="county" id="county" style="width: 150px;height: 25px;border-radius: 5px;"></select><br>
<input type="hidden" id="provinceVal" value="360000">
<input type="hidden" id="cityVal" value="360700">
<input type="hidden" id="countyVal" value="360721">
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
    var config = {
        province: '#province',
        city: "#city",
        county: "#county",
        provinceVal: "#provinceVal",
        cityVal: "#cityVal",
        countyVal: "#countyVal",
    };

    function lucasajax(url, type, opObj, opValObj) {
        var lucsaVal = $(opValObj).val();
        $.ajax({
            url: url, type: type, data: '', dataType: 'json', success: function (rsp) {
                $(rsp).each(function (index, val) {
                    if(lucsaVal == val.area_code) {
                        opObj.append('<option value="' + val.area_code + '" selected>' + val.area_name + '</option>');
                    }else{
                        opObj.append('<option value="' + val.area_code + '">' + val.area_name + '</option>');
                    }

                })
            },
            error: function () {
                alert('网络异常！')
            }
        })
    }

    function changeDowntown(province, opObj, cityVal) {
        var cityContainer = $(config.city);
        var countyContainer = $(config.county);
        cityContainer.html('').append('<option value=""> -- 请选择 -- </option>');
        countyContainer.html('').append('<option value=""> -- 请选择 -- </option>');
        lucasajax('/downtowns/' + province, 'get', opObj, cityVal);
    }

    function changeCounty(downtown, opObj, countyVal) {
        var countyContainer = $(config.county);
        countyContainer.html('');
        countyContainer.append('<option value=""> -- 请选择 -- </option>');
        lucasajax('/countys/' + downtown, 'get', opObj, countyVal);
    }


    function citySelector(userConfig) {
        var provinceContainer = $(config.province);
        var cityContainer = $(config.city);
        var countyContainer = $(config.county);
        var provinceVal = $(config.provinceVal);
        var cityVal = $(config.cityVal);
        var countyVal = $(config.countyVal);
        if (userConfig) {
            config = userConfig;
        }
        //初始化
        provinceContainer.html('').append('<option value=""> -- 请选择 -- </option>');
        cityContainer.html('').append('<option value=""> -- 请选择 -- </option>');
        countyContainer.html('').append('<option value=""> -- 请选择 -- </option>');
        lucasajax('/provinces', 'get', provinceContainer, provinceVal);

        var lucasprovince = $(provinceVal).val();
        if(provinceVal) changeDowntown(lucasprovince, cityContainer, cityVal);

        var lucascity = $(cityVal).val();
        if(provinceVal) changeCounty(lucascity, countyContainer, countyVal);

        // 省份切换
        $(config.province).on('change', function () {
            changeDowntown($(this).val(), cityContainer, cityVal)
        })
        // 市区切换
        $(config.city).on('change', function () {
            changeCounty($(this).val(), countyContainer, countyVal)
        })
    }

    citySelector();
</script>
</body>
</html>
