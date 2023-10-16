@guest
    @if((!session('status') && !$errors->any()) && (config('services.keycloak.base_url') && config('services.keycloak.realm') && config('services.keycloak.client_id')))
        <script src="https://sso.tyuiu.ru/js/keycloak.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function(event) {
                const keycloak = new Keycloak({
                    url: '{{ config('services.keycloak.base_url') }}',
                    realm: '{{ config('services.keycloak.realm') }}',
                    clientId: '{{ config('services.keycloak.client_id') }}'
                });

                keycloak.init({
                    onLoad: 'check-sso',
                    silentCheckSsoRedirectUri: window.location.origin + '/silent-check-sso.html'
                }).then(function(authenticated) {
                    if(authenticated) {
                        window.location.replace(`{{ route('Admin.AuthSilentCallback') }}?token=${keycloak.token}&sid=${keycloak.sessionId}`);
                        // console.log(keycloak);
                    }
                }).catch(function(error) {
                    //console.log(error)
                });
            });
        </script>
    @endif
@endguest