{
  description = "";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
  };

  outputs = { self, nixpkgs }:
    let
      system = "x86_64-linux";
      
      pkgs = nixpkgs.legacyPackages.${system};
    in
    {
      devShells.${system}.default = pkgs.mkShell {
        
        buildInputs = with pkgs; [
                  php
                  phpPackages.composer
                  tailwindcss_4          
                ];

        shellHook = ''
          echo "Initialize a config file (tailwind.config.js)"
          tailwindcss init
          
          echo "Watch for changes and build your CSS"
          tailwindcss -i ./src/input.css -o ./public/output.css --watch >> /dev/null & 
                    
          sleep 1
          
          echo "PHP server is started"
          kitty --hold sh -c "php -S localhost:8000 -t public" &
          
        '';
      };
    };
}