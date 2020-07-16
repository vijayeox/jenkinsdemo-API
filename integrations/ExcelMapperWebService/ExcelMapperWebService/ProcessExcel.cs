using Microsoft.Office.Core;
using Microsoft.Office.Interop.Excel;
using System;
using System.IO;
using System.Diagnostics;
using System.Text.RegularExpressions;
using Range = Microsoft.Office.Interop.Excel.Range;
using Shape = Microsoft.Office.Interop.Excel.Shape;
using ArrowHeadWebService;
using RestSharp;
using System.Text.Json;
using System.Text.Json.Serialization;
using System.Collections.Generic;
using Microsoft.VisualBasic.CompilerServices;

namespace ProcessExcel
{
    public class ErrorDetails
    {
        public string worksheet { get; set; }
        public int row { get; set; }
        public string message { get; set; }
        public string file { get; set; }

        public int errortype { get; set; }

        public static implicit operator List<object>(ErrorDetails v)
        {
            throw new NotImplementedException();
        }
    }
    class ProcessExcel
    {
        public string baseDirectory { get; set; }
        public bool Errorflag { get; set; }

        private Settings _settings;

        private string _fileId;
        private List<ErrorDetails> _errordetails = new List<ErrorDetails>();

        public ProcessExcel(Settings settings)
        {
            _settings = settings;
        }



        public void processFile(string baseFolder, string filename, string fileId)
        {
            Errorflag = false;
            _fileId = fileId;
            this.baseDirectory = baseFolder;
            string fname = System.IO.Path.GetFileName(filename);
            string fnameSource = DateTime.Now.ToString("MMddyyyyhhmmss") + "-" + _settings.dwFile;
            LogProcess("Processing file : " + fname + " --------------------------------- ");
            LogProcess("Moving file : " + fname + " ----" + this.baseDirectory + @"\Upload\" + fname + " To" + this.baseDirectory + @"\Templates\" + fnameSource);
            File.Move(this.baseDirectory + @"\Upload\" + fname, this.baseDirectory + @"\Templates\" + fnameSource);
            LogProcess("moved");
            _settings.dwFile = fnameSource;
            Application excel = new Application();
            string folder = "\\Templates\\";
            string path = this.baseDirectory + @folder;
            LogProcess("Starting Excel Application");
            LogProcess("Trying to Open " + path + _settings.mappingFile);
            Workbook wb = excel.Workbooks.Open(path + _settings.mappingFile);
            LogProcess("Opened Excel File");
            try
            {
                //  excel.Run("Main");
                RunMapping(wb);
                wb.Close();
                excel.Quit();
                File.Move(this.baseDirectory + @"\Templates\" + fnameSource, this.baseDirectory + @"\Processed\" + fnameSource);
            }
            catch (Exception e)
            {
                LogProcess("In Catch - DeleteErroFiles done");
                excel.DisplayAlerts = false;
                wb.Close();
                excel.Quit();
                LogProcess("In Catch - Workbook Closed");
                File.Move(this.baseDirectory + @"\Templates\" + fnameSource, this.baseDirectory + @"\Errors\" + fnameSource);
                PostError(fnameSource, 0,e.Message);
                LogProcess("In Catch - Moved to Error Folder");
                Logerror("Error - In Catch - " + e.Message, fnameSource);
            }
            finally
            {
                Process[] excelProcs = Process.GetProcessesByName("EXCEL");
                foreach (Process proc in excelProcs)
                {
                    proc.Kill();
                }

            }

        }


        private void RunMapping(Workbook wb)
        {

            wb.Activate();
            foreach (Worksheet vMappingWorkSheet in wb.Worksheets)
            {
                if (vMappingWorkSheet.Name != "ErrorLog")
                {
                    wb.Activate();
                    ReadMapping(vMappingWorkSheet, wb);
                }
            }


        }


        private void ReadMapping(Worksheet vMappingWorkSheet, Workbook vCurWorkbook)
        {

            string[,] aMapping = new string[1000, 10];
            vMappingWorkSheet.Activate();
            string vFolder = vCurWorkbook.Path;
            string vFile = (vMappingWorkSheet.Cells[1, 2] as Range).Value.ToString();
            try
            {
                int vRow = 3;
                while ((vMappingWorkSheet.Cells[vRow, 1] as Range).Value != null)
                {
                    for (int vCol = 1; vCol <= 5; vCol++)
                    {
                        if ((vMappingWorkSheet.Cells[vRow, vCol] as Range).Value != null)
                        {
                            aMapping[vRow - 2, vCol] = (vMappingWorkSheet.Cells[vRow, vCol] as Range).Value.ToString();
                        }
                    }
                    vRow += 1;
                }
                int vTotalCount = vRow - 3;
                MapData(vFolder, vFile, aMapping, vTotalCount, vCurWorkbook, vMappingWorkSheet);
            }
            catch (Exception e)
            {
                PostError(vFile, 1,e.Message);
            }
        }



        private void MapData(string vFolder, string vFile, string[,] aMapping, int vTotalCount, Workbook vCurWorkbook, Worksheet vMappingWorkSheet)
        {
            Application excel = new Application();

            Workbook wbDealer = excel.Workbooks.Open(vFolder + "\\" + _settings.dwFile);
            string fCarrier = DateTime.Now.ToString("MMddyyyyhhmmss") + "-" + vFile;
            File.Copy(vFolder + "\\" + vFile, vFolder + "\\" + fCarrier);
            Workbook wbCarrier = excel.Workbooks.Open(vFolder + "\\" + fCarrier);
            wbCarrier.Activate();
            try
            {
                for (int i = 1; i <= vTotalCount; i++)
                {
                    Copycells(vCurWorkbook, wbDealer, wbCarrier, aMapping, i);
                }
                wbCarrier.Save();
                wbCarrier.Close();
                wbDealer.Close();
                PostFile(vFolder, fCarrier);
                File.Move(vFolder + "\\" + fCarrier, this.baseDirectory + "\\Processed\\" + fCarrier);
                excel.Quit();
                System.Runtime.InteropServices.Marshal.ReleaseComObject(excel);
            }
            catch (Exception e)
            {
                if (wbDealer != null)
                {
                    wbDealer.Close();
                }
                if (wbCarrier != null)
                {
                    Logerror("Error:" + e, wbCarrier.Name);
                    wbCarrier.Close();
                }
                else
                {
                    Logerror("Error:" + e, wbDealer.Name);
                }
                excel.Quit();
                PostError(wbCarrier.Name, 1,e.Message);
            }
        }

        private void Copycells(Workbook vCurWorkbook, Workbook wbDealer, Workbook wbCarrier, string[,] aMapping, int i)
        {
            string vSourceSheet = aMapping[i, 1];
            string vDestSheet = aMapping[i, 3];
            string vSourceRange = aMapping[i, 2];
            string vDestRange = aMapping[i, 4];
            string vSpecial = aMapping[i, 5];
            try
            {
                switch (vSpecial)
                {
                    case "Table":
                        Range vSRange = (wbDealer.Worksheets[vSourceSheet] as Worksheet).Range[vSourceRange];
                        Range vDRange = (wbCarrier.Worksheets[vDestSheet] as Worksheet).Range[vDestRange];
                        while ((vSRange[1, 1] as Range).Value != null)
                        {
                            if ((vSRange[1, 1] as Range).Value.ToString() == "") { break; }
                            vSRange.Value = vDRange.Value;
                            vSRange = vSRange.Offset[1, 0];
                            vDRange = vDRange.Offset[1, 0];
                        }
                        break;
                    case "Checkbox":
                        if ((wbDealer.Worksheets[vSourceSheet] as Worksheet).Range[vSourceRange].Value != null)
                        {
                            string vVal = (wbDealer.Worksheets[vSourceSheet] as Worksheet).Range[vSourceRange].Value.ToString();
                            if (vVal == "1" || vVal.ToUpper() == "YES")
                            {
                                Microsoft.Office.Interop.Excel.Shape shp = (wbCarrier.Worksheets[vDestSheet] as Worksheet).Shapes.Item(vDestRange);
                                shp.ControlFormat.Value = 1;
                            }
                        }
                        break;
                    default:
                        (wbCarrier.Worksheets[vDestSheet] as Worksheet).Range[vDestRange].Value = (wbDealer.Worksheets[vSourceSheet] as Worksheet).Range[vSourceRange].Value;
                        //string val = wbCarrier.Worksheets[vSourceSheet].Range[vSourceRange].Value;
                        break;
                }
            }
            catch (Exception e)
            {
                ErrorDetails errordetail = new ErrorDetails();
                errordetail.file = wbCarrier.Name;
                errordetail.row = i + 2;
                errordetail.worksheet = vDestSheet;
                errordetail.message = e.ToString();
                errordetail.errortype = 2;
                _errordetails.Add(errordetail);
            }

        }



        public void Logerror(string errormsg, string fname)
        {
            fname = fname.Replace(".", "-");
            using (StreamWriter errorfile = File.AppendText(this.baseDirectory + @"\Errors\" + fname + "-error.txt"))
            {
                errorfile.WriteLine(errormsg);
                errorfile.Close();
            }
            Errorflag = true;
        }

        public void LogProcess(string message = "")
        {
            try
            {
                if (_settings.logging == 1)
                {
                    using (StreamWriter logFile = File.AppendText(this.baseDirectory + @"\processed\logs.txt"))
                    {
                        logFile.WriteLine(DateTime.Now.ToString("H:m") + " -> " + message);
                        logFile.Close();
                    }
                }
            }
            catch (Exception e)
            {
                throw new System.ArgumentException("Error in logProcess:" + e.Message);
            }
        }

        public class CallbackData
        {
            public string appId { get; set; }
            public string orgId { get; set; }
            public string fileId { get; set; }
            public string filename { get; set; }
            public byte status { get; set; }
            public List<ErrorDetails> _errorlist { get; set; }
        }

        private void PostFile(string folder, string fileName)
        {
            Console.WriteLine(_settings.postURL);
            var client = new RestClient(_settings.postURL + "app/" +  _settings.appId + "/delegate/TriggerTemplateService");
            client.Timeout = -1;

            var request = new RestRequest(Method.POST);
            request.AddFile("file", folder + "\\" + fileName);
            request.AlwaysMultipartFormData = true;
            var postCallbackData = JsonSerializer.Serialize(new CallbackData()
            {
                status = 1,
                fileId = _fileId,
                orgId = _settings.orgId,
                appId =  _settings.appId,
                filename = fileName,
                _errorlist = this._errordetails

            }) ;
            Console.WriteLine(postCallbackData);
            request.AddParameter("data", postCallbackData, ParameterType.RequestBody);

            string fname = fileName.Replace(".", "-");
            LogProcess(postCallbackData);
            this._errordetails.Clear();
            IRestResponse response = client.Execute(request);
            Console.WriteLine(response.Content);
            LogProcess("Sending Post:" + fileName);
        }

        private void PostError(string fileName, int errortype, string message)
        {
            var client = new RestClient(_settings.postURL);
            client.Timeout = -1;
            var request = new RestRequest(Method.POST);
            ErrorDetails errordetail = new ErrorDetails()
            {
                file = fileName,
                row = 0,
                message = message,
                worksheet = "",
                errortype = errortype
            };
            var postCallbackData = JsonSerializer.Serialize(new CallbackData()
            {
                status = 0,
                fileId = _fileId,
                orgId = _settings.orgId,
                appId =  _settings.appId,
                filename = fileName,    
                _errorlist = new List<ErrorDetails> { errordetail}
            });
            request.AddParameter("data", postCallbackData, ParameterType.RequestBody);
            IRestResponse response = client.Execute(request);
        }

    }
}