using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;

namespace ArrowHeadWebService
{
    public class Settings
    {
        public int logging { get; set; }
        public string postURL { get; set; }
        public string mappingFile { get; set; }
        public string dwFile { get; set; }
        public string commands { get; set; }
        public string appUUID { get; set; }
        public string delegateName { get; set; }
        public string token { get; set; }
    }
}
