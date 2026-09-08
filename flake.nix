{
  description = "Development environment for PHP Blog with Tailwind CSS";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      supportedSystems = [ "x86_64-linux" "aarch64-linux" "x86_64-darwin" "aarch64-darwin" ];
      forAllSystems = nixpkgs.lib.genAttrs supportedSystems;
    in
    {
      devShells = forAllSystems (system:
        let
          pkgs = nixpkgs.legacyPackages.${system};
        in
        {
          default = pkgs.mkShell {
            buildInputs = with pkgs; [
              php
              phpPackages.composer
              tailwindcss_4
              concurrently
            ];

            shellHook = ''
              if [ ! -d "vendor" ] && [ -f "composer.json" ]; then
                echo "📦 Installing PHP dependencies via Composer..."
                composer install
              fi

              if [ ! -f "src/input.css" ]; then
                echo "📝 Creating initial src/input.css..."
                mkdir -p src
                echo -e "@import \"tailwindcss\";" > src/input.css
              fi

              alias watch-css="tailwindcss -i ./src/input.css -o ./public/output.css --watch"
              alias serve="php -S localhost:8000 -t public"
              alias dev="concurrently -n 'CSS,PHP' -c 'blue,green' \"tailwindcss -i ./src/input.css -o ./public/output.css --watch\" \"php -S localhost:8000 -t public\""

              echo ""
              echo "🚀 Dev Environment Ready!"
              echo "  • Run 'dev'       -> Start Tailwind watcher & PHP server concurrently"
              echo "  • Run 'serve'     -> Start PHP server only"
              echo "  • Run 'watch-css' -> Start Tailwind compiler only"
              echo ""
            '';
          };
        }
      );
    };
}