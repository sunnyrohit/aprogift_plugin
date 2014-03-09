
function validateShares(total) {
    var shares = $(".shareInput");
    mytotal = 0;
    for (var i = 0; i < shares.length; i++) {
        if (validateShare($(shares[i]).val())) {
            var val = parseFloat($(shares[i]).val());
            mytotal += val;
        }
        else {
            alert("Please enter whole numbers for share value.");
            return false;
        }
    }
    if (total == mytotal) return true;
    else {
        alert("Gift cost is " + total + ".\nThe shares add up to " + mytotal + ".\nPlease recheck.");
        return false;
    }
}

function validateName(name) {
    if (name.length == 0) return false;
    var ret = true;
    var charpos = name.search("[^A-Za-z\\s]");
    if (name.length > 0 && charpos >= 0)
    {
        ret = false;
    } //if 
    return ret;
}

function validateShare(share) {
    if (!isNaN(parseFloat(share))) {
        return true;
    }
    else {
        return false;
    }
}

function validateEmail(email)
{
    var splitted = email.match("^(.+)@(.+)$");
    if (splitted == null) return false;
    if (splitted[1] != null)
    {
        var regexp_user = /^\"?[\w-_\.]*\"?$/;
        if (splitted[1].match(regexp_user) == null) return false;
    }
    if (splitted[2] != null)
    {
        var regexp_domain = /^[\w-\.]*\.[A-Za-z]{2,4}$/;
        if (splitted[2].match(regexp_domain) == null)
        {
            var regexp_ip = /^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
            if (splitted[2].match(regexp_ip) == null) return false;
        } // if
        return true;
    }
    return false;
}
