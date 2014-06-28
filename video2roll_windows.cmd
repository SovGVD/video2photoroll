@rem Run without parameters for help!

@set CMD_NAME=%~nx0%
@set DIR=%~dp0%
@set FFMPEG=%DIR%ffmpeg
@set IMAGEMAGICK=%DIR%imagemagick

@if [%6] EQU [] goto Usage
@set inputfile=%1
@set outputdir=%2
@set tStart=%3
@set tStop=%4
@set resultFrames=%5
@set width=%6

@SHIFT
@SHIFT
@SHIFT
@SHIFT
@SHIFT
@SHIFT
@set OPTIONS=
@:loop1
@if [%1] == [] goto end_loop1
@set OPTIONS=%OPTIONS% %1
@SHIFT
@goto loop1

@:end_loop1

@echo OPTIONS=%OPTIONS%

rem goto :Convert

@setlocal ENABLEDELAYEDEXPANSION

@for /F "tokens=1* delims=:" %%a in ("!tStart!") do @set /a tStartSec=%%a*60+%%b
@echo Start=!tStartSec!

@for /F "tokens=1* delims=:" %%a in ("!tStop!") do @set /a tStopSec=%%a*60+%%b
@echo Stop=!tStopSec!

@set /a tLen=tStopSec-tStartSec
@echo Length=!tLen!

@set /a resultFPS=(1000*resultFrames+(tLen/2))/tLen
@set resultFPS=000%resultFPS%
@echo resultFPS*1000=%resultFPS%
@set resultFPS=%resultFPS:~0,-3%.%resultFPS:~-3%
@echo resultFPS=%resultFPS%

@setlocal DISABLEDELAYEDEXPANSION

@%FFMPEG%\bin\ffmpeg.exe -i %inputfile% -r %resultFPS% -ss %tStartSec%  -to %tStopSec% %OPTIONS% -f image2 %outputdir%\i%%04d.jpeg

@:Convert
@echo Set current path: %outputdir%
@pushd %outputdir%
@set fileSet=
@setlocal ENABLEDELAYEDEXPANSION
@for %%i in (*.jpeg) do @set fileSet=!fileSet! %%i
@set fileSet=%fileSet:~1%
@echo %fileSet% | %IMAGEMAGICK%\convert @- -sharpen 0x1.0 -filter Lanczos -distort resize 640x +append out.jpg

@for /f "tokens=*" %%a in ('@%IMAGEMAGICK%\identify -format "%%[fx:h]" out.jpg') do @set height=%%a

@echo Height=%height%
@set output_name=out_%resultFrames%_%width%_%height%.jpg
@ren out.jpg %output_name%

@popd

@echo -------------------------------------------------------------
@echo Result image: %outputdir%\%output_name%

@goto Quit

@:Usage
@echo ----------------------------------------------------------------------
@echo This script makes a scrollable picture from video for SovGVD's script.
@echo       Made by Andrey Prikupets, 2014. Many thanks to SovGVD!
@echo    See more: http://forum.rcdesign.ru/blogs/79585/blog19018.html
@echo ----------------------------------------------------------------------
@echo Usage:
@echo          %CMD_NAME% input_file_name output_folder start_time stop_time output_frames output_width [ffmpeg_options]
@echo Note: 
@echo          start_time and stop_time have format: MM:SS
@echo Example: 
@echo          %CMD_NAME% C:\Capture\gopr7489.mp4 C:\VideoOut 1:38 1:55 36 640 -vf "crop=iw-200:ih-200:100:200"
@echo Utilities: 
@echo          ffmpeg should be copied to: %FFMPEG%
@echo          imagemagic should be copied to: %IMAGEMAGICK%

:Quit
