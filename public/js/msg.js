
let delete_msg = "削除してよろしいですか？";
let create_msg = "登録しますか？";
let update_msg = "更新しますか？";

function checkSubmit(msg){
    if(window.confirm(msg)){
        return true;
    } else {
        return false;
    }
}
