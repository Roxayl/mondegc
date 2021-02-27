REM Ce script permet d'exporter la différence entre deux commits, identifiés par leur SHA, dans un fichier zip.
setlocal enabledelayedexpansion
set /P shacommitinitial="SHA du commit master initial: "
set /P shacommitfinal="SHA du commit master final: "
set output=
for /f "delims=" %%a in ('git diff --diff-filter=ACMR --name-only %shacommitinitial% %shacommitfinal%') do ( if exist %%~sa set output=!output! "%%a" )
git archive -o update.zip HEAD %output%
endlocal