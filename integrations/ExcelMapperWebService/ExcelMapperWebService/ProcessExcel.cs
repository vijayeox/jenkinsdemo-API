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
using Newtonsoft.Json.Linq;
using System.Linq;
using System.Runtime.InteropServices;

namespace ProcessExcel
{
    public class ErrorDetails
    {
        public string worksheet { get; set; }
        public string destinationrange { get; set; }
        public string message { get; set; }
        public string file { get; set; }
        public string folder { get; set; }
        public int errortype { get; set; }

        public static implicit operator List<object>(ErrorDetails v)
        {
            throw new NotImplementedException();
        }
    }

    public class CallbackData
    {
        public string filename { get; set; }
        public string fileid { get; set; }
        public byte status { get; set; }
        public string appid { get; set; }
        public string orgid { get; set; }
        public List<ErrorDetails> errorlist { get; set; }
    }
    class ProcessExcel
    {
        public string baseDirectory { get; set; }
        public bool Errorflag { get; set; }

        private Settings _settings;

        private CallbackData _callback;
        public ProcessExcel(Settings settings)
        {
            _settings = settings;
        }



        public void processFile(string baseFolder, JsonElement jsonbody)
        {
            Errorflag = false;
            this.baseDirectory = baseFolder;
            string carrierfile = "";
            try
            {
                string jsontext = jsonbody.ToString();
                JObject parsedJson = JObject.Parse(jsontext);
                carrierfile = parsedJson["mapping"]["filename"].ToString();            
                _callback = new CallbackData();
                _callback.filename = carrierfile;
                _callback.fileid = parsedJson["fileId"].ToString();
                _callback.appid = parsedJson["appId"].ToString();
                _callback.orgid = parsedJson["orgId"].ToString();
                _callback.errorlist = new List<ErrorDetails>();
                List<string> myvariables = new List<string>();
                string mystr = _settings.postURL;
                while (mystr.Contains("{"))
                {
                    string variable = mystr.Split('{', '}')[1];
                    string prop = (string)_callback.GetType().GetProperty(variable).GetValue(_callback, null);
                    mystr = mystr.Replace("{" + variable + "}", prop);
                };
                _settings.postURL = mystr;
                object data = parsedJson["mapping"]["data"];
                IList<JToken> mappingdata = parsedJson["mapping"]["data"].Children().ToList();
                MapData(carrierfile, mappingdata);
            }
            catch (Exception e)
            {
                PostError(carrierfile, 0,e.Message);
            }
    //        finally
    //        {
    //            Process[] excelProcs = Process.GetProcessesByName("EXCEL");
    //           foreach (Process proc in excelProcs)
   //             {
   //                 proc.Kill();
   //             }
    //        }

        }

        [System.Runtime.InteropServices.DllImport("user32.dll", CharSet = CharSet.Auto, SetLastError = true)]
        public static extern int GetWindowThreadProcessId(HandleRef handle, out int processId);

        private void MapData(string carrierfile, IList<JToken> mappingdata)
        {
            Application excel = new Application();
            string vFolder = this.baseDirectory + "\\Templates\\";
            string fCarrier = DateTime.Now.ToString("MMddyyyyhhmmss") + "-" + carrierfile;
            File.Copy(vFolder + "\\" + carrierfile, vFolder + "\\" + fCarrier);
            Workbook wbCarrier = excel.Workbooks.Open(vFolder + "\\" + fCarrier);
            HandleRef hwnd = new HandleRef(excel, (IntPtr)excel.Hwnd);
            int pid;
            GetWindowThreadProcessId(hwnd, out pid);
            wbCarrier.Activate();

            try
            {
                foreach(JToken mapdata in mappingdata)
                {
                    Copycells(wbCarrier, mapdata);
                }
                wbCarrier.Save();
                wbCarrier.Close();
                PostFile(vFolder, fCarrier);
                File.Move(vFolder + "\\" + fCarrier, this.baseDirectory + "\\Processed\\" + fCarrier);
            }
            catch (Exception e)
            {
                if (wbCarrier != null)
                {
               //     Logerror("Error:" + e, wbCarrier.Name);
                    wbCarrier.Close();
                }

                PostError(carrierfile, 1,e.Message);
            }
            finally
            {
                excel.Quit();
                System.Runtime.InteropServices.Marshal.ReleaseComObject(excel);
                Process excelProcs = Process.GetProcessById(pid);
                excelProcs.Kill();
            }
        }

        private void Copycells(Workbook wbCarrier, JToken mapdata)
        {
            string vVal;
            string vDestSheet = mapdata["pageName"].ToString();
            string vDestRange = mapdata["cell"].ToString();
            string vSpecial = mapdata["type"].ToString();
            try
            {
                switch (vSpecial)
                {
                    case "DataGrid":
                        JToken value = mapdata["value"];
                        List<List<string>> values = mapdata["value"].ToObject<List<List<string>>>();
                        CopyTable(wbCarrier, vDestSheet, vDestRange,values);
                        break;
                    case "Checkbox":
                        vVal = mapdata["value"].ToString();
                        if (vVal == "1" || vVal.ToUpper() == "YES" || vVal.ToUpper() == "TRUE")
                        {
                                Microsoft.Office.Interop.Excel.Shape shp = (wbCarrier.Worksheets[vDestSheet] as Worksheet).Shapes.Item(vDestRange);
                                shp.ControlFormat.Value = 1;
                            }
                        break;
                    default:
                        vVal = mapdata["value"].ToString();
                        if (vVal!="") { 
                            (wbCarrier.Worksheets[vDestSheet] as Worksheet).Range[vDestRange].Value = vVal;
                        }
                        //string val = wbCarrier.Worksheets[vSourceSheet].Range[vSourceRange].Value;
                        break;
                }
            }
            catch (Exception e)
            {
                ErrorDetails errordetail = new ErrorDetails();
                errordetail.file = wbCarrier.Name;
                errordetail.folder = this.baseDirectory;
                errordetail.worksheet = vDestSheet;
                errordetail.message = e.ToString();
                errordetail.destinationrange = vDestRange;
                errordetail.errortype = 2;
                _callback.errorlist.Add(errordetail);
            }

        }

        public void CopyTable(Workbook wbCarrier, string vDestSheet, string vDestRange,  List<List<string>> values)
        {
            try
            {
                int rowindex = (wbCarrier.Worksheets[vDestSheet] as Worksheet).Range[vDestRange].Row;
                int colindex = (wbCarrier.Worksheets[vDestSheet] as Worksheet).Range[vDestRange].Column;
                foreach (List<string> row in values)
                {
                    int i = colindex;
                    foreach (string value in row)
                    {
                        if (value != "")
                        {
                            (wbCarrier.Worksheets[vDestSheet] as Worksheet).Cells[rowindex, i] = value;
                        }

                        i++;
                    }
                    rowindex++;
                }
            } catch 
            {
                throw;
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



        private void PostFile(string folder, string fileName)
        {
            try
            {
                Console.WriteLine(_settings.postURL);
                var client = new RestClient(_settings.postURL + "app/" + _callback.appid + "/pipeline");
                //       var client = new RestClient(_settings.postURL);
                client.Timeout = -1;

                var request = new RestRequest(Method.POST);
                request.AddFile("outputfile", folder + "\\" + fileName);
                // request.AddHeader("Authorization", "Bearer " + _settings.token);
                request.AlwaysMultipartFormData = true;
                _callback.status = 1;
                var postCallbackData = JsonSerializer.Serialize(_callback);
                request.AddParameter("data", postCallbackData, ParameterType.RequestBody);
                string fname = fileName.Replace(".", "-");
                LogProcess(postCallbackData);
                IRestResponse response = client.Execute(request);
                LogProcess("Sending Post:" + fileName);
            } catch (Exception e) {
                Logerror(e.Message, fileName);
            }
        }

        private void PostError(string fileName, int errortype, string message)
        {
            try
            {
                var client = new RestClient(_settings.postURL);
                client.Timeout = -1;
                var request = new RestRequest(Method.POST);
                ErrorDetails errordetail = new ErrorDetails()
                {
                    file = fileName,
                    folder = this.baseDirectory,
                    destinationrange = "",
                    message = message,
                    worksheet = "",
                    errortype = errortype
                };
                _callback.errorlist = new List<ErrorDetails> { errordetail };
                var postCallbackData = JsonSerializer.Serialize(_callback);

                request.AddParameter("data", postCallbackData, ParameterType.RequestBody);
                IRestResponse response = client.Execute(request);
            }
            catch (Exception e)
            {
                Logerror(e.Message, fileName);
            }
        }

    }
}