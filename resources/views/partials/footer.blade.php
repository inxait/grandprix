<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>@Copyright - ACDelco Colombia</p>
                <a href="{{asset('terms/CIRCULAR_ACD_018_19_GRAND_PRIX_ACDelco_2019.pdf')}}"
                   target="_blank" rel="noopener noreferrer" class="terms">
                   Términos y condiciones
                </a>
            </div>
        </div>
    </div>
    <div class="socials">
        <p>
            Síguenos en:
        </p>
        <a href="{{Settings::getByKey('link-facebook')->value}}" target="_blank" rel="noopener noreferrer">
            <div class="icon facebook"></div>
        </a>
        <a href="{{Settings::getByKey('link-twitter')->value}}" target="_blank" rel="noopener noreferrer">
            <div class="icon twitter"></div>
        </a>
    </div>
</footer>
