using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.IO;
using System.Linq;
using System.Net.Http.Headers;
using System.Net.Mime;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Hosting;
using Microsoft.AspNetCore.Http;
using Microsoft.AspNetCore.Mvc;
using Microsoft.Extensions.Configuration;

namespace ArrowHeadWebService.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class FileUpload1Controller : ControllerBase
    {
        public static IWebHostEnvironment _environment;
        public static Settings _settings;
        
        public FileUpload1Controller(IWebHostEnvironment environment, IConfiguration configuration)
        {
            _environment = environment;
            _settings = new Settings();
            var customConfig = configuration.GetSection("CustomSettings");
            _settings.dwFile= configuration["CustomSettings:dwFile"];
            _settings.logging = Int32.Parse(customConfig["logging"].ToString());
            _settings.mappingFile = customConfig["mappingFile"].ToString();
            _settings.postURL = customConfig["postURL"].ToString();
        }

        public class FileUploadAPI
        {
            public IFormFile files { get; set; }
        }

        [HttpPost]
        public async Task<ContentResult> Post([FromForm] FileUploadAPI objFile)
        {
            try
            {
                if (objFile.files !=null)
                {
                    if (!Directory.Exists(_environment.WebRootPath + "\\Upload\\"))
                    {
                        Directory.CreateDirectory(_environment.WebRootPath + "\\Upload\\");
                    }
                    using (FileStream fileStream = System.IO.File.Create(_environment.WebRootPath + "\\Upload\\" + objFile.files.FileName))
                    {
                        objFile.files.CopyTo(fileStream);
                        fileStream.Flush();
                    }
                    ProcessExcel.ProcessExcel pExcel = new ProcessExcel.ProcessExcel(_settings);
                    new Task(() => { pExcel.processFile(_environment.WebRootPath, objFile.files.FileName); }).Start();                    
                    return Content("{\"Status\":1,\"Message\":\"" + objFile.files.FileName+" file Uploaded\"}", "application/json");
                }
                else
                {
                    return Content("{\"Status\":0,\"Message\":\"No File Uploaded\"}", "application/json");
                }
            } catch (Exception ex)
            {
                return Content("{\"Status\":0,\"Message\":\""+ex+"\"}", "application/json");
            }
        }
    }
}
