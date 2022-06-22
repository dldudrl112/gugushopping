var option_add = false;
var supply_add = false;
var isAndroid = (navigator.userAgent.toLowerCase().indexOf("android") > -1);

$(function() {
    // 선택옵션
    /* 가상커서 ctrl keyup 이베트 대응 */
    /*
    $(document).on("keyup", "select.it_option", function(e) {
        var sel_count = $("select.it_option").size();
        var idx = $("select.it_option").index($(this));
        var code = e.keyCode;
        var val = $(this).val();

        option_add = false;
        if(code == 17 && sel_count == idx + 1) {
            if(val == "")
                return;

            sel_option_process(true);
        }
    });
    */

    /* 키보드 접근 후 옵션 선택 Enter keydown 이벤트 대응 */
    $(document).on("keydown", "select.it_option", function(e) {
        var sel_count = $("select.it_option").length;
        var idx = $("select.it_option").index($(this));
        var code = e.keyCode;
        var val = $(this).val();

        option_add = false;
        if(code == 13 && sel_count == idx + 1) {
            if(val == "")
                return;

            sel_option_process(true);
        }
    });

    if(isAndroid) {
        $(document).on("touchend", "select.it_option", function() {
            option_add = true;
        });
    } else {
        $(document).on("mouseup", "select.it_option", function() {
            option_add = true;
        });
    }

    $(document).on("change", "select.it_option", function() {
        var sel_count	=	$("select.it_option").length;
        var idx			=	$("select.it_option").index($(this));
        var val			=	$(this).val();
        var gs_id		=	$("input[name='gs_id[]']").val();

        // 선택값이 없을 경우 하위 옵션은 disabled
        if(val == ""){
            $("select.it_option:gt("+idx+")").val("").attr("disabled", true);
            return;
        }

        // 하위주문옵션로드
        if(sel_count > 1 && (idx + 1) < sel_count) {

            var opt_id = "";

            // 상위 옵션의 값을 읽어 옵션id 만듬
            if(idx > 0) {
                $("select.it_option:lt("+idx+")").each(function() {
                    if(!opt_id)
                        opt_id = $(this).val();
                    else
                        opt_id += chr(30)+$(this).val();
                });

                opt_id += chr(30)+val;

            }else if(idx == 0) {
                opt_id = val;
            }

			var opt_subject	=	$("select.it_option:eq("+(idx+1)+")").prev	().text();

            $.post(
                "/shop/view_option.php",
                { gs_id: gs_id, opt_id: opt_id, idx: idx, sel_count: sel_count , opt_subject: opt_subject },
                function(data) {

                    $("select.it_option").eq(idx+1).empty().html(data).attr("disabled", false);

                    // select의 옵션이 변경됐을 경우 하위 옵션 disabled
                    if(idx+1 < sel_count) {
                        var idx2 = idx + 1;
                        $("select.it_option:gt("+idx2+")").val("").attr("disabled", true);
                    }
                }
            );

        } else if((idx + 1) == sel_count) { // 주문옵션처리
            if(option_add && val == "")
                return;

            var info = val.split(",");
            // 재고체크
            if(parseInt(info[2]) < 1) {
                alert("선택하신 주문옵션상품은 재고가 부족하여 구매할 수 없습니다.");
                return false;
            }

            if(option_add)
                sel_option_process(true);
        }
    });

    // 추가옵션
    /* 가상커서 ctrl keyup 이베트 대응 */
    /*
    $(document).on("keyup", "select.it_supply", function(e) {
        var $el = $(this);
        var code = e.keyCode;
        var val = $(this).val();

        supply_add = false;
        if(code == 17) {
            if(val == "")
                return;

            sel_supply_process($el, true);
        }
    });
    */

    /* 키보드 접근 후 옵션 선택 Enter keydown 이벤트 대응 */
    $(document).on("keydown", "select.it_supply", function(e) {
        var $el = $(this);
        var code = e.keyCode;
        var val = $(this).val();

        supply_add = false;
        if(code == 13) {
            if(val == "")
                return;

            sel_supply_process($el, true);
        }
    });

    if(isAndroid) {
        $(document).on("touchend", "select.it_supply", function() {
            supply_add = true;
        });
    } else {
        $(document).on("mouseup", "select.it_supply", function() {
            supply_add = true;
        });
    }

    $(document).on("change", "select.it_supply", function() {
        var $el = $(this);
        var val = $(this).val();

        if(val == "")
            return;

        if(supply_add)
            sel_supply_process($el, true);
    });

    // 수량변경 및 삭제
	$(document).on("click", ".sit_opt_list  .option-con .calculator a", function() {
        var mode = $(this).attr("data");
        var this_qty, max_qty = 9999, min_qty = 1;
        var $el_qty = $(this).closest("li").find("input[name^=ct_qty]");
        var stock = parseInt($(this).closest("li").find("input.io_stock").val());

		switch(mode) {
            case "plus":
                this_qty = parseInt($el_qty.val().replace(/[^0-9]/, "")) + 1;
                if(this_qty > stock) {
                    alert("재고수량 보다 많은 수량을 구매할 수 없습니다.");
                    this_qty = stock;
                }

                if(this_qty > max_qty) {
                    this_qty = max_qty;
                    alert("최대 구매수량은 "+number_format(String(max_qty))+" 이하 입니다.");
                }

                $el_qty.val(this_qty);
                price_calculate();
                break;

            case "minus":
                this_qty = parseInt($el_qty.val().replace(/[^0-9]/, "")) - 1;
                if(this_qty < min_qty) {
                    this_qty = min_qty;
                    alert("최소 구매수량은 "+number_format(String(min_qty))+" 이상 입니다.");
                }
                $el_qty.val(this_qty);
                price_calculate();
                break;

            case "삭제":
                if(confirm("선택하신 옵션항목을 삭제하시겠습니까?")) {
                    var $el = $(this).closest("li");
                    var del_exec = true;

                    if($("#option_set_list .sit_opt_list").length > 0) {
                        // 주문옵션이 하나이상인지
                        if($el.hasClass("sit_opt_list")) {
                            if($(".sit_opt_list").length <= 1)
                                del_exec = false;
                        }
                    }

                    if(del_exec) {
                        $el.closest("li").remove();
                        price_calculate();
                    } else {
                        alert("주문옵션은 하나이상이어야 합니다.");
                        return false;
                    }
                }
                break;

            default:
                alert("올바른 방법으로 이용해 주십시오.");
                break;
        }
    });

	// 수량직접입력
	$(document).on("keyup", "input[name^=ct_qty]", function() {
        var val= $(this).val();

        if(val != "") {
            if(val.replace(/[0-9]/g, "").length > 0) {
                alert("수량은 숫자만 입력해 주십시오.");
                $(this).val(1);
            } else {
                var d_val = parseInt(val);
                if(d_val < 1 || d_val > 9999) {
                    alert("수량은 1에서 9999 사이의 값으로 입력해 주십시오.");
                    $(this).val(1);
                } else {
                    var stock = parseInt($(this).closest("li").find("input.io_stock").val());
                    if(d_val > stock) {
                        alert("재고수량 보다 많은 수량을 구매할 수 없습니다.");
                        $(this).val(stock);
                    }
                }
            }

            price_calculate();
        }
    });
});

// 주문옵션 추가처리
function sel_option_process(add_exec)
{
    var id			=	"";
    var value, info, sel_opt, item, price, stock, amt, run_error = false;
    var option	=	sep = "";

    info	=	$("select.it_option:last").val().split(",");

    $("select.it_option").each(function(index) {

        value	=	$(this).val();
        item	=	$(this).closest(".option-con").find("label").text();


        if(!value) {
            run_error = true;
            return false;
        }

        // 옵션선택정보
        sel_opt = value.split(",")[0];

        if(id == "") {
            id = sel_opt;
        } else {
            id += chr(30)+sel_opt;
            sep = " / ";
        }

        option += sep + item + ":" + sel_opt;
    });

    if(run_error) {
        alert(item+"을(를) 선택해 주십시오.");
        return false;
    }

    price = info[1];
    stock = info[2];
	amt = info[3];

    if(add_exec) {
        if(same_option_check(option))
            return;

        add_sel_option(0, id, option, price, stock, amt);
    }
}

// 추가옵션 추가처리
function sel_supply_process($el, add_exec)
{
    var val = $el.val();
    var item = $el.closest(".vi_txt_li dl").find("dt label").text();

    if(!val) {
        alert(item+"을(를) 선택해 주십시오.");
        return;
    }

    var info = val.split(",");

    // 재고체크
    if(parseInt(info[2]) < 1) {
        alert(info[0]+"은(는) 재고가 부족하여 구매할 수 없습니다.");
        return false;
    }

    var id = item+chr(30)+info[0];
    var option = item+" : "+info[0];
    var price = info[1];
    var stock = info[2];
	var amt = info[3];

    if(add_exec) {
        if(same_option_check(option))
            return;

        add_sel_option(1, id, option, price, stock, amt);
    }
}

// 선택된 옵션 출력
function add_sel_option(type, id, option, price, stock, amt)
{
    var item_code = $("input[name='gs_id[]']").val();
    var opt = "";
    var li_class	=	"sit_opt_list";

    if(type)
        li_class		=	"sit_spl_list";




    var opt_prc;
	var pamt = parseInt(price) + parseInt(amt);
    if(parseInt(pamt) >= 0)
        opt_prc = "+"+number_format(String(pamt))+"원";
    else
        opt_prc = number_format(String(pamt))+"원";

    if(li_class =="sit_spl_list"){
		if($("input[name^=io_value]").length == 0){
			$("#it_supply_1").val("");
			alert("옵션을 선택해 주세요.");
			return false;
		}
		price_calculate_gugu();
		return false;
	}


	if($("input[name^=io_value]").length >= 1){
			$(".sit_opt_list").remove();
	}
	
    opt += "<li class=\""+li_class+" vi_txt_li\">\n";
    opt += "<input type=\"hidden\" name=\"io_type["+item_code+"][]\" value=\""+type+"\">\n";
    opt += "<input type=\"hidden\" name=\"io_id["+item_code+"][]\" value=\""+id+"\">\n";
    opt += "<input type=\"hidden\" name=\"io_value["+item_code+"][]\" value=\""+option+"\">\n";
    opt += "<input type=\"hidden\" class=\"io_price\" value=\""+price+"\">\n";
    opt += "<input type=\"hidden\" class=\"io_stock\" value=\""+stock+"\">\n";
	opt += "<span class=\"sit_opt_subj option-name\">수량</span>\n";
	opt += "<span class=\"option-con\">\n";
	opt += "	<span class=\"calculator\">\n";
	opt += "			<a href=\"javascript:void(0)\"  data=\"minus\" class=\"calc-min defbtn_minus\">-</a>\n";
	opt += "			<input type=\"numbe\" name=\"ct_qty["+item_code+"][]\" class=\"sale-cnt\" value=\"1\">\n";
	opt += "			<a href=\"javascript:void(0)\" data=\"plus\" class=\"calc-plus defbtn_plus\">+</a>\n";
	//opt += "			<button type=\"button\" class=\"defbtn_delete\">삭제</button>\n";
	opt += "	</span>\n";
	opt += "</span>\n";
    opt += "</li>\n";

    if($(".option-box .option_set_list").length < 1) {

        //$("#option_set_added").prepend("<ul class=\"option_set_list\"></ul>").trigger("create");
        $("#option_set_added").prepend(opt).trigger("create");


		}else{

			if(type) {

				if($(".option_set_list .sit_spl_list").length > 0) {
					$(".option_set_list .sit_spl_list:last").after(opt);
				}else{
					if($(".option_set_list .sit_opt_list").length > 0) {
						$(".option_set_list .sit_opt_list:last").after(opt);
					} else {
						$("#option_set_added").prepend(opt).trigger("create");
					}
				}

			}else{

				if($(".option_set_list .sit_opt_list").length > 0) {
					$(".option_set_list .sit_opt_list:last").after(opt);
				} else {
					if($(".option_set_list .sit_spl_list").length > 0) {
						$(".option_set_list .sit_spl_list:first").before(opt);
					} else {
						$("#option_set_added").prepend(opt).trigger("create");
					}
				}
			}
		}

    price_calculate();
}

// 동일주문옵션있는지
function same_option_check(val)
{
    var result = false;
    $("input[name^=io_value]").each(function() {
        if(val == $(this).val()) {
            result = true;
            return false;
        }
    });

    if(result)
        alert(val+" 은(는) 이미 추가하신 옵션상품입니다.");

    return result;
}

// 가격계산
function price_calculate()
{
    var it_price = parseInt($("input#it_price").val());

    if(isNaN(it_price))
        return;

    var $el_prc = $("input.io_price");
    var $el_qty = $("input[name^=ct_qty]");
    var $el_type = $("input[name^=io_type]");
    var price, type, qty, total = 0;

    $el_prc.each(function(index) {
        price = parseInt($(this).val());
        qty = parseInt($el_qty.eq(index).val());
        type = $el_type.eq(index).val();

        if(type == "0") { // 주문옵션
            total += (it_price + price) * qty;
        } else { // 추가옵션
            total += price * qty;
        }
    });

	var beforeStr = $("#it_supply_1").val();
	if(beforeStr){
		var afterStr = beforeStr.split(',');
	
		if(!afterStr[0]){
			afterStr[0] = $("#supply_item_gugu").val();
		}
		var period_price =total/afterStr[0];
	}else{
		if($("#supply_item_gugu").val()){
			var period_price =total/$("#supply_item_gugu").val();
		}
	}

	$("#sit_tot_views").show();
	if($("input[name^=io_value]").length >= 1){
			$(".price").empty().html(number_format(String(total))+"원");
			if(period_price){
				$(".period_price").empty().html("월 구독 "+number_format(String(period_price))+"원");
			}
	}
    $("#sit_tot_price").empty().html(number_format(String(total))+"원");
}

// gugu 가격계산
function price_calculate_gugu()
{
    var it_price = parseInt($("input#it_price").val());

	
    if(isNaN(it_price))
        return;

    var $el_prc = $("input.io_price");
    var $el_qty = $("input[name^=ct_qty]");
    var $el_type = $("input[name^=io_type]");
    var price, type, qty, total = 0;

    $el_prc.each(function(index) {
        price = parseInt($(this).val());
        qty = parseInt($el_qty.eq(index).val());
        type = $el_type.eq(index).val();

        if(type == "0") { // 주문옵션
            total += (it_price + price) * qty;
        } else { // 추가옵션
            total += price * qty;
        }
    });


	var beforeStr = $("#it_supply_1").val();
	var afterStr = beforeStr.split(',');
	var period_price =total/afterStr[0];
	
	$("#sit_tot_views").show();
	$(".price").empty().html(number_format(String(total))+"원");
	$(".period_price").empty().html("월 구독 "+number_format(String(period_price))+"원");
    $("#sit_tot_price").empty().html(number_format(String(total))+"원");
}


// php chr() 대응
function chr(code)
{
    return String.fromCharCode(code);
}
