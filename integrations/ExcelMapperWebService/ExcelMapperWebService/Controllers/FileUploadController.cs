﻿using System;
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
using System.Text.Json;



namespace ArrowHeadWebService.Controllers
{
    [Route("api/[controller]")]
    [ApiController]

    public class FileUploadController : ControllerBase
    {
        public static IWebHostEnvironment _environment;
        public static Settings _settings;
        
        public FileUploadController(IWebHostEnvironment environment, IConfiguration configuration)
        {
            _environment = environment;
            _settings = new Settings();
            var customConfig = configuration.GetSection("CustomSettings");
            _settings.dwFile= configuration["CustomSettings:dwFile"];
            _settings.logging = Int32.Parse(customConfig["logging"].ToString());
            _settings.mappingFile = customConfig["mappingFile"].ToString();
            _settings.postURL = customConfig["postURL"].ToString();
        }




        [HttpPost]
        public ContentResult Post([FromBody] JsonElement jsonbody)
        {
            try
            {
                string jsontext = jsonbody.ToString();
                ProcessExcel.ProcessExcel pExcel = new ProcessExcel.ProcessExcel(_settings);
                new Task(() => { pExcel.processFile(_environment.WebRootPath, jsonbody); }).Start();
                return Content("{\"Status\":1,\"Message\":\"" + "File Sent For Processing\"}", "application/json");
            } catch (Exception e)
            {
                return Content("{\"Status\":0,\"Message\":\"" + e.Message + "\"}", "application/json");
            }
        }

    }
}
