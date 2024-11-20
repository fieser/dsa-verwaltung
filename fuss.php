<?php
?>
</div>


                                <div class="ilMessageBox">


                                </div>
                                <div class="ilStartupSection">

</div>


                        </div>
                </div>
        </div>
</div>
<div id="minheight"></div>
<footer class="ilFooter">
        <div class="container">
 <?php         
      echo "<div class='row ilFooterContainer'><bdo class='' dir='ltr'>powered by ".$_SESSION['schule_kurzname'] ."</bdo> |";

      echo "<a href='".$url_impressum."' target='_blank'>Impressum</a> | <i>Made in 2nd Corona</i>";
?>



</div>
        </div>
</footer>
</div>

<script>
var acc = document.getElementsByClassName("accordion2");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel2 = this.nextElementSibling;
    if (panel2.style.display === "block") {
      panel2.style.display = "none";
    } else {
      panel2.style.display = "block";
    }
  });
}
</script

</body>
</html>
<?php
?>
