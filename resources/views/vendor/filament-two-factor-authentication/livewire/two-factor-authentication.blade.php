<div>
    <x-filament::section :aside="$aside">
        <x-slot name="heading">
            {{__('Autenticación de dos factores')}}
        </x-slot>

        <x-slot name="description">
            {{__('Agregue seguridad adicional a su cuenta utilizando la autenticación de dos factores.')}}
        </x-slot>

        <div class="">
            @if($this->isConfirmingSetup)
                <h2 class="text-xl font-medium mb-4">
                    {{__('Terminar de habilitar la autenticación de dos factores.')}}
                </h2>

                <p class="text-sm mb-4">
                    {{__("Cuando la autenticación de dos factores está habilitada, se le solicitará un token aleatorio seguro durante la autenticación. Puede recuperar este token desde la aplicación Google Authenticator de su teléfono.")}}
                </p>

                <p class="text-sm font-semibold mb-4">
                    {{__("Para terminar de habilitar la autenticación de dos factores, escanee el siguiente código QR usando la aplicación de autenticación de su teléfono o ingrese la clave de configuración y proporcione el código OTP generado.")}}
                </p>

                <div class="mb-4">
                    {!! $this->getUser()->twoFactorQrCodeSvg() !!}
                </div>

                <form wire:submit="confirmSetup">
                    <div class="mb-4">
                        {{ $this->form }}
                    </div>
                    <div class="flex gap-2">
                        {{$this->confirmSetup}}
                        {{$this->cancelSetup}}
                    </div>
                </form>
            @elseif($this->enableTwoFactorAuthentication->isVisible())
                <h2 class="text-xl font-medium mb-4">
                    {{__('No ha habilitado la autenticación de dos factores.')}}
                </h2>

                <p class="text-sm mb-4">
                    {{__("Cuando la autenticación de dos factores está habilitada, se le solicitará un token aleatorio seguro durante la autenticación. Puede recuperar este token desde la aplicación Google Authenticator de su teléfono.")}}
                </p>

                {{$this->enableTwoFactorAuthentication}}
            @elseif($this->disableTwoFactorAuthentication->isVisible())
                <h2 class="text-xl font-medium mb-4">{{__('Ha habilitado la autenticación de dos factores.')}}</h2>

                <p class="text-sm mb-4">
                    {{__('Guarde estos códigos de recuperación en un administrador de contraseñas seguro. Pueden utilizarse para recuperar el acceso a su cuenta si pierde su dispositivo de autenticación de dos factores.')}}
                </p>

                <div class="mb-4 p-4 bg-gray-100 rounded-md">
                    @foreach($this->getUser()->recoveryCodes() as $code)
                        <p class="text-sm font-medium mb-2">{{$code}}</p>
                    @endforeach
                </div>

                {{$this->generateNewRecoveryCodes}}

                {{$this->disableTwoFactorAuthentication}}
            @endif
        </div>
    </x-filament::section>

    <x-filament-actions::modals />

    {{-- Success is as dangerous as failure. --}}
</div>
