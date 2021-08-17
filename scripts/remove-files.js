let fs = require('fs'),
  destFolder = require('./structure.js').destFolder,
  removeFiles = function(path, event) {
  if (event && typeof event === 'string') {
    let fileName = path.replace(/.*\\/, ''),          // img.jpg
      fileSrc = path.replace(/.*src\\/, '')           // img\img.jpg
                    .replace(/[^\\]*$/, '')           // img\
                    .replace(/\\/g, '\/'),           // img/
      destination = destFolder + fileSrc + fileName,  // dist/img/img.jpg
      fileFullSrc = path.replace(/\\/g, '\/');       // src/img/statistics-slide-1.svg
      console.log(destination);
    if (event === 'unlink') {
      fs.unlink(destination, function(err){
        if (err) {
            console.log(`File ${fileFullSrc} was removed`);
            console.log(`File \"${fileName}\" is not found in \"${destFolder + fileSrc}\"`);
        } else {
            console.log(`File \"${fileName}\" was removed from \"${destFolder + fileSrc}\"`);
        }
      });
    }
  }
};

module.exports = removeFiles;