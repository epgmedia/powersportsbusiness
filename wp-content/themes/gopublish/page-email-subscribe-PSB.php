<?php
/*
Template Name: Email Subscription
*/

get_header();
?>


<div id="content">

    <div id="contentleft">

        <div class="postarea">

            <?php include(TEMPLATEPATH."/breadcrumb.php");?>

            <form method="post" name="profileform" action="https://EPGMediaLLC.informz.net/clk/remote_post.asp">
                <span style="color:red;font-size:10px;">*&nbsp;Required</span><br />
                <h1>Enter Your Personal Information</h1>
                <p>
                    <label for="email">Email:<span style="color:red;">*</span></label>
                    <input alt="Email Address" type="text" id="email" name="email" maxlength="100" value="" >
                </p>
		        <p>
                    <label for="firstName">First Name:<span style="color:red;">*</span></label>
                    <input alt="First Name" type="text" id="firstName" name="personal_5784" value="" maxlength="500">
                </p>
                <p>
                    <label for="lastName">Last Name:<span style="color:red;">*</span></label>
                    <input alt="Last Name" type="text" id="lastName" name="personal_5785" value="" maxlength="500">
                </p>
                <p>
                    <label for="company">Company:</label>
                    <input alt="Company" type="text" id="company" name="personal_5786" value="" maxlength="500">
                </p>
                <p>
                    <label for="state">State:<span style="color:red;">*</span></label>
                    <select name="demo_810" id="state">
                        <option value="36647">AK</option>
                        <option value="36648">AL</option>
                        <option value="36649">AR</option>
                        <option value="36650">AZ</option>
                        <option value="36651">CA</option>
                        <option value="36652">CO</option>
                        <option value="36653">CT</option>
                        <option value="36654">DC</option>
                        <option value="36655">DE</option>
                        <option value="36656">FL</option>
                        <option value="36657">GA</option>
                        <option value="36658">HI</option>
                        <option value="36659">IA</option>
                        <option value="36660">ID</option>
                        <option value="36661">IL</option>
                        <option value="36662">IN</option>
                        <option value="36663">KS</option>
                        <option value="36664">KY</option>
                        <option value="36665">LA</option>
                        <option value="36666">MA</option>
                        <option value="36667">MD</option>
                        <option value="36668">ME</option>
                        <option value="36669">MI</option>
                        <option value="36670">MN</option>
                        <option value="36671">MO</option>
                        <option value="36672">MS</option>
                        <option value="36673">MT</option>
                        <option value="36674">NC</option>
                        <option value="36675">ND</option>
                        <option value="36676">NE</option>
                        <option value="36677">NH</option>
                        <option value="36678">NJ</option>
                        <option value="36679">NM</option>
                        <option value="36680">NV</option>
                        <option value="36681">NY</option>
                        <option value="36682">OH</option>
                        <option value="36683">OK</option>
                        <option value="36684">OR</option>
                        <option value="36685">PA</option>
                        <option value="36686">RI</option>
                        <option value="36687">SC</option>
                        <option value="36688">SD</option>
                        <option value="36689">TN</option>
                        <option value="36690">TX</option>
                        <option value="36691">UT</option>
                        <option value="36692">VA</option>
                        <option value="36693">VT</option>
                        <option value="36694">WA</option>
                        <option value="36695">WI</option>
                        <option value="36696">WV</option>
                        <option value="36697">WY</option>
                        <option value="36698">AB</option>
                        <option value="36699">BC</option>
                        <option value="36700">MB</option>
                        <option value="36701">NB</option>
                        <option value="36702">NF</option>
                        <option value="36703">NS</option>
                        <option value="36704">NT</option>
                        <option value="36705">NU</option>
                        <option value="36706">ON</option>
                        <option value="36707">PE</option>
                        <option value="36708">QC</option>
                        <option value="36709">SK</option>
                        <option value="36710">YT</option>
                        <option value="36711">AA</option>
                        <option value="36712">AE</option>
                        <option value="36713">AP</option>
                        <option value="36714">AS</option>
                        <option value="36715">FM</option>
                        <option value="36716">GU</option>
                        <option value="36717">MH</option>
                        <option value="36718">MP</option>
                        <option value="36719">PR</option>
                        <option value="36720">PW</option>
                        <option value="36721">VI</option>
                    </select>
                <p>
                <p>
                    <label for="zipCode">Zip Code:<span style="color:red;">*</span></label>
                    <input alt="Zip Code" id="zipCode" type="text" name="personal_6146" value="" size="30" maxlength="500">
                </p>
                <p>
                    <label for="priBusiness">Which one of the following best describes your primary business?<span style="color:red;">*</span></label>
                    <select name="demo_860" id="priBusiness">
                        <option value="38340">Dealer/Retailer of Powersports Equipment and/or Accessory/Apparel/Parts and/or Service/Repair Shop</option>
                        <option value="38341">Accessory/Apparel/Parts Dealer/Retailer Only</option>
                        <option value="38342">Service/Repair Shop Only</option>
                        <option value="38343">Powersports Manufacturer and/or Distributor</option>
                        <option value="38344">Other</option>
                    </select>
                </p>
                <p>
                    For which of the following powersports manufacturers are you an authorized dealer:<span style="color:red;">*</span><br/>
                    <input alt="Aprilia" type=checkbox name="demo_862" value="38353">Aprilia><br>
                    <input alt="Arctic Cat" type=checkbox name="demo_862" value="38354">Arctic Cat<br>
                    <input alt="BMW" type=checkbox name="demo_862" value="38355">BMW<br>
                    <input alt="BRP" type=checkbox name="demo_862" value="38356">BRP<br>
                    <input alt="Buell" type=checkbox name="demo_862" value="38357">Buell<br>
                    <input alt="Ducati" type=checkbox name="demo_862" value="38358">Ducati<br>
                    <input alt="Harley-Davidson" type=checkbox name="demo_862" value="38359">Harley-Davidson<br>
                    <input alt="Honda" type=checkbox name="demo_862" value="38360">Honda<br>
                    <input alt="Kawasaki" type=checkbox name="demo_862" value="38361">Kawasaki<br>
                    <input alt="KTM" type=checkbox name="demo_862" value="38362">KTM<br>
                    <input alt="Polaris" type=checkbox name="demo_862" value="38363">Polaris<br>
                    <input alt="Suzuki" type=checkbox name="demo_862" value="38364">Suzuki<br>
                    <input alt="Triumph" type=checkbox name="demo_862" value="38365">Triumph<br>
                    <input alt="Yamaha" type=checkbox name="demo_862" value="38366">Yamaha<br>
                    <input alt="None of the above" type=checkbox name="demo_862" value="38372">None of the above
                </p>
                      
                <p>
                    Check all of the powersports products you handle:<span style="color:red;">*</span><br/>
                    <input alt="Personal Watercraft" type=checkbox name="demo_861" value="38345">Personal Watercraft<br>
                    <input alt="Snowmobiles" type=checkbox name="demo_861" value="38346">Snowmobiles<br>
                    <input alt="Motorcycles" type=checkbox name="demo_861" value="38347">Motorcycles<br>
                    <input alt="ATVs" type=checkbox name="demo_861" value="38348">ATVs<br>
                    <input alt="Utility Side-by-Sides" type=checkbox name="demo_861" value="38349">Utility Side-by-Sides<br>
                    <input alt="Scooters" type=checkbox name="demo_861" value="38350">Scooters<br>
                    <input alt="Parts & Accessories" type=checkbox name="demo_861" value="38351">Parts & Accessories<br>
                    <input alt="Service Shop" type=checkbox name="demo_861" value="38352">Service Shop<br>
                    <input alt="None" type=checkbox name="demo_861" value="38371">None<br>
                </p>
	            <h2>Select Your Interests</h2>
                <p>
                    <input alt="Powersports Business Enewsletters and Communications" type="checkbox" value="64911" name="interests" checked />
                    <a href='#' onClick="ShowDescriptions('http://EPGMediaLLC.informz.net/EPGMediaLLC',64911,1147); return false;">Powersports Business Enewsletters and Communications</a>
                </p>

                <p>
                    <input alt="Email communications from other reputable powersports companies" type="checkbox" value="64954" name="interests" checked />
                    <a href='#' onClick="ShowDescriptions('http://EPGMediaLLC.informz.net/EPGMediaLLC',64954,1147); return false;">Email communications from other reputable powersports companies</a>
                </p>

                <p>
                    You will receive tailored information according to your interests.
                </p>
                <p>
                    <input type="submit" border=0 value="Next >>" name="update" style="background-color: #214b7b; color: #ffffff; border-style: groove; font-size: 16pt;border-color: #ffffff">
                </p>
                <input type="hidden" name="formats" value="3">
                <input type="hidden" name="OptoutInfo" value="">
                <input type=hidden name=fid value=2085>
                <input type=hidden name=b value=3678>
                <input type=hidden name=returnUrl value="http://www.powersportsbusiness.com/you-are-subscribed/?zmsg=1">
            </form>


            <script type="text/javascript">
                function ShowDescriptions(SubDomain,val, brid) {
                    myWindow = window.open(SubDomain + '/description.asp?brid=' + brid + '&id=' + val, 'Description', 'location=no,height=180,width=440,resizeable=no,scrollbars=yes,dependent=yes');
                    myWindow.focus()
                }

                /***********************************************
                 * Textarea Maxlength script- © Dynamic Drive (www.dynamicdrive.com)
                 * This notice must stay intact for legal use.
                 * Visit http://www.dynamicdrive.com/ for full source code
                 ***********************************************/
                function ismaxlength(obj, mlength) {
                    if (obj.value.length > mlength)
                        obj.value = obj.value.substring(0, mlength)
                }

                function moveCaret(event, objThisField, objNextField, objPrevField, nSize)
                {
                    var keynum;
                    if(window.event) // IE
                        keynum = event.keyCode;
                    else if(event.which) // Netscape/Firefox/Opera
                        keynum = event.which;
                    if (keynum == 37 || keynum == 39 || keynum == 38 || keynum == 40 || keynum == 8) //left, right, up, down arrows, backspace
                    {
                        var nCaretPosition = getCaretPosition(objThisField);
                        if (keynum == 39 && nCaretPosition == nSize)
                            moveToNextField(objNextField);
                        if ((keynum == 37 || keynum == 8) && nCaretPosition == 0)
                            moveToPrevField(objPrevField);
                        return;
                    }
                    if (keynum == 9) //Tab
                        return;
                    if (objThisField.value.length >= nSize && objNextField != null)
                        moveToNextField(objNextField);
                }
                function moveToNextField(objNextField)
                {
                    if (objNextField == null)
                        return;
                    objNextField.focus();
                    if (document.selection) //IE
                    {
                        oSel = document.selection.createRange ();
                        oSel.moveStart ('character', 0);
                        oSel.moveEnd ('character', objNextField.value.length);
                        oSel.select();
                    }
                    else
                    {
                        objNextField.selectionStart = 0;
                        objNextField.selectionEnd = objNextField.value.length;
                    }
                }
                function moveToPrevField(objPrevField)
                {
                    if (objPrevField == null)
                        return;
                    objPrevField.focus();
                    if (document.selection) //IE
                    {
                        oSel = document.selection.createRange ();
                        oSel.moveStart ('character', 0);
                        oSel.moveEnd ('character', objPrevField.value.length);
                        oSel.select ();
                    }
                    else
                    {
                        objPrevField.selectionStart = 0;
                        objPrevField.selectionEnd = objNextField.value.length;
                    }
                }
                function getCaretPosition(objField)
                {
                    var nCaretPosition = 0;
                    if (document.selection) //IE
                    {
                        var oSel = document.selection.createRange ();
                        oSel.moveStart ('character', -objField.value.length);
                        nCaretPosition = oSel.text.length;
                    }
                    if (objField.selectionStart || objField.selectionStart == '0')
                        nCaretPosition = objField.selectionStart;
                    return nCaretPosition;
                }

                fullURL = document.URL
                sAlertStr = ''
                nLoc = fullURL.indexOf('&')
                if (nLoc == -1)
                    nLoc = fullURL.length
                if (fullURL.indexOf('zreq=') > 0){
                    sRequired = fullURL.substring(fullURL.indexOf('zreq=')+5, nLoc)
                    if (sRequired.length > 0){
                        sRequired = ',' + sRequired.replace('%20',' ')
                        sRequired = sRequired.replace(/,/g,'\n  - ')
                        sAlertStr = 'The following item(s) are required: '+sRequired + '\n'
                    }
                }
                if (fullURL.indexOf('zmsg=') > 0) {
                    sMessage = fullURL.substring(fullURL.indexOf('zmsg=')+5, fullURL.length)
                    if (sMessage.length > 0) {
                        sMessage = sMessage.replace(/%20/g, ' ')
                        sMessage = sMessage.replace(/%0A/g, '\n')
                        sAlertStr = sAlertStr + sMessage
                    }
                }

                if (sAlertStr.length > 0)
                    alert(sAlertStr)
            </script>

        </div>

    </div>

    <?php include(TEMPLATEPATH."/sidebar.php");?>

</div>

<?php
get_footer();