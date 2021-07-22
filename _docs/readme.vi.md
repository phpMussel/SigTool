### Cách cài đặt:

Trước khi cài đặt, vui lòng kiểm tra các yêu cầu. Nếu những điều này không được đáp ứng, SigTool sẽ không hoạt động chính xác.

#### Yêu cầu:
- __Cho SigTool &lt;=1.0.2:__ PHP &gt;=7.0.3 với hỗ trợ cho phar (PHP &gt;=7.2.0 được khuyến nghị).
- __Cho SigTool 1.0.3:__ PHP &gt;=7.0.3 (PHP &gt;=7.2.0 được khuyến nghị).
- __Cho SigTool v2:__ PHP &gt;=7.2.0.
- __Tất cả các phiên bản:__ *Ít nhất* &gt;=681 MB RAM khả dụng (nhưng, ít nhất &gt;=1 GB được khuyến nghị).
- __Tất cả các phiên bản:__ Khoảng ~300 MB dung lượng đĩa trống (con số này có thể thay đổi giữa các lần lặp lại của cơ sở dữ liệu chữ ký).
- __Tất cả các phiên bản:__ Khả năng vận hành PHP ở chế độ CLI (ví dụ, dấu nhắc lệnh, thiết bị đầu cuối, trình bao, bash, vv).

Cách được khuyến nghị để cài đặt SigTool là thông qua Composer.

`composer require phpmussel/sigtool`

Ngoài ra, bạn có thể sao chép kho lưu trữ, hoặc tải xuống tập tin zip, trực tiếp từ GitHub.

---


### Cách sử dụng:

Lưu ý rằng SigTool KHÔNG phải là một ứng dụng web dựa trên PHP (web-app)! SigTool là một ứng dụng CLI dựa trên PHP (CLI-app) dự định sẽ được sử dụng với terminal, shell, vv. Nó có thể được gọi bằng cách gọi các nhị phân PHP với tập tin `SigTool.php` làm đối số đầu tiên của nó:

`$ php SigTool.php`

Thông tin trợ giúp sẽ được hiển thị khi gọi SigTool, sẽ liệt kê các cờ có sẵn (đối số thứ hai) có thể được sử dụng khi gọi SigTool.

Các cờ có sẵn:
- Không có đối số: Hiển thị thông tin trợ giúp này.
- `x`: Trích xuất các tập tin chữ ký từ `daily.cvd` và `main.cvd`.
- `p`: Xử lý các tập tin chữ ký để sử dụng với phpMussel.
- `m`: Tải xuống `main.cvd` trước khi xử lý.
- `d`: Tải xuống `daily.cvd` trước khi xử lý.
- `u`: Cập nhật SigTool (tải xuống `SigTool.php` lại và die; không kiểm tra được thực hiện).

Đầu ra được sản xuất là các tập tin chữ ký phpMussel khác nhau được tạo trực tiếp từ cơ sở dữ liệu chữ ký ClamAV, theo hai hình thức:
- Chữ ký có thể được chèn trực tiếp vào thư mục `/vault/signatures/`.
- Bản sao của các tập tin chữ ký được nén bằng GZ có thể được sử dụng để cập nhật repository `phpMussel/Signatures`.

Đầu ra được sản xuất trực tiếp vào cùng thư mục với `SigTool.php`. Các tập tin nguồn và tất cả các tập tin tạm thời sẽ bị xóa trong quá trình hoạt động (vì thế, nếu bạn muốn giữ bản sao của `daily.cvd` và `main.cvd`, bạn nên tạo bản sao trước khi xử lý tập tin chữ ký).

Khi sử dụng SigTool để tạo tập tin chữ ký mới, có thể trình quét vi-rút máy tính của bạn có thể cố gắng xóa hoặc cách ly các tập tin chữ ký mới. Điều này xảy ra bởi vì đôi khi, các tập tin chữ ký có thể chứa dữ liệu rất giống với dữ liệu mà trình quét vi-rút của bạn tìm kiếm khi quét. Tuy nhiên, các tập tin chữ ký được tạo bởi SigTool không chứa bất kỳ mã thực thi nào và hoàn toàn lành tính. Nếu bạn gặp phải vấn đề này, bạn có thể thử tắt tạm thời trình quét vi-rút của bạn, hoặc định cấu hình trình quét vi-rút của bạn để đưa danh sách trắng vào thư mục nơi bạn đang tạo tập tin chữ ký mới.

Nếu tập tin YAML `signatures.dat` được bao gồm trong cùng một thư mục khi xử lý, thông tin phiên bản và tổng kiểm tra sẽ được cập nhật tương ứng (vì thế, khi sử dụng SigTool để cập nhật repository `phpMussel/Signatures`, điều này nên được bao gồm).

*Lưu ý: Nếu bạn là người dùng phpMussel, xin hãy nhớ rằng tập tin chữ ký phải được KÍCH HOẠT để làm việc chính xác! Nếu bạn đang sử dụng SigTool để tạo ra các tập tin chữ ký mới, bạn có thể "kích hoạt" chúng bằng cách liệt kê chúng trong cấu hình "Active" của cấu hình phpMussel. Nếu bạn đang sử dụng trang cập nhật của front-end để cài đặt và cập nhật tập tin chữ ký, bạn có thể "kích hoạt" chúng trực tiếp từ trang cập nhật của front-end. Tuy nhiên, sử dụng cả hai phương pháp là không cần thiết. Ngoài ra, cho hiệu suất tối ưu phpMussel, chúng tôi khuyên bạn chỉ nên sử dụng tập tin chữ ký mà bạn cần cho cài đặt của bạn (ví dụ, nếu một số loại tập tin cụ thể bị liệt vào danh sách đen, có thể bạn sẽ không cần các tập tin chữ ký tương ứng với loại tập tin đó; phân tích các tập tin mà sẽ bị chặn dù sao là công việc không cần thiết và có thể làm chậm đáng kể quá trình quét).*

Trình diễn video để sử dụng SigTool có trên YouTube: __[youtu.be/f2LfjY1HzRI](https://youtu.be/f2LfjY1HzRI)__

---


### Danh sách các tập tin chữ ký được tạo ra bởi SigTool:
Tập tin chữ ký | Sự miêu tả
---|---
clamav.hdb | Nhắm mục tiêu các tất cả các loại tập tin; Làm việc với tập tin băm.
clamav.htdb | Nhắm mục tiêu các tập tin HTML; Làm việc với dữ liệu được chuẩn hoá HTML.
clamav_regex.htdb | Nhắm mục tiêu các tập tin HTML; Làm việc với dữ liệu được chuẩn hoá HTML; Chữ ký có thể chứa các biểu thức chính quy.
clamav.mdb | Nhắm mục tiêu các tập tin PE; Làm việc với siêu dữ liệu phần PE.
clamav.ndb | Nhắm mục tiêu các tất cả các loại tập tin; Làm việc với dữ liệu được chuẩn hoá ANSI.
clamav_regex.ndb | Nhắm mục tiêu các tất cả các loại tập tin; Làm việc với dữ liệu được chuẩn hoá ANSI; Chữ ký có thể chứa các biểu thức chính quy.
clamav.db | Nhắm mục tiêu các tất cả các loại tập tin; Làm việc với dữ liệu thông thường.
clamav_regex.db | Nhắm mục tiêu các tất cả các loại tập tin; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_elf.db | Nhắm mục tiêu các tập tin ELF; Làm việc với dữ liệu thông thường.
clamav_elf_regex.db | Nhắm mục tiêu các tập tin ELF; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_email.db | Nhắm mục tiêu các tập tin EML; Làm việc với dữ liệu thông thường.
clamav_email_regex.db | Nhắm mục tiêu các tập tin EML; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_exe.db | Nhắm mục tiêu các tập tin PE; Làm việc với dữ liệu thông thường.
clamav_exe_regex.db | Nhắm mục tiêu các tập tin PE; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_graphics.db | Nhắm mục tiêu các tập tin hình ảnh; Làm việc với dữ liệu thông thường.
clamav_graphics_regex.db | Nhắm mục tiêu các tập tin hình ảnh; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_java.db | Nhắm mục tiêu các tập tin Java; Làm việc với dữ liệu thông thường.
clamav_java_regex.db | Nhắm mục tiêu các tập tin Java; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_macho.db | Nhắm mục tiêu các tập tin Mach-O; Làm việc với dữ liệu thông thường.
clamav_macho_regex.db | Nhắm mục tiêu các tập tin Mach-O; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_ole.db | Nhắm mục tiêu các đối tượng OLE; Làm việc với dữ liệu thông thường.
clamav_ole_regex.db | Nhắm mục tiêu các đối tượng OLE; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_pdf.db | Nhắm mục tiêu các tập tin PDF; Làm việc với dữ liệu thông thường.
clamav_pdf_regex.db | Nhắm mục tiêu các tập tin PDF; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.
clamav_swf.db | Nhắm mục tiêu các tập tin SWF; Làm việc với dữ liệu thông thường.
clamav_swf_regex.db | Nhắm mục tiêu các tập tin SWF; Làm việc với dữ liệu thông thường; Chữ ký có thể chứa các biểu thức chính quy.

---


### Lưu ý về phần mở rộng tập tin chữ ký:
*Thông tin này sẽ được mở rộng trong tương lai.*

- __cedb__: Tập tin chữ ký kéo dài phức tạp (đây là một định dạng được tạo ra cho phpMussel, và không liên quan gì đến cơ sở dữ liệu chữ ký ClamAV; SigTool không tạo ra bất kỳ tập tin chữ ký sử dụng phần mở rộng này; chúng được viết bằng tay cho repository `phpMussel/Signatures`; `clamav.cedb` có chứa các bản sửa đổi của một số chữ ký lỗi thời từ các phiên bản trước của cơ sở dữ liệu chữ ký ClamAV được coi là vẫn còn có ích cho phpMussel). Tập tin chữ ký hoạt động với các quy tắc khác nhau dựa trên siêu dữ liệu mở rộng do phpMussel tạo ra sử dụng phần mở rộng này.
- __db__: Tập tin chữ ký tiêu chuẩn (chúng được trích xuất từ các tập tin chữ ký `.ndb` chứa trong `daily.cvd` và `main.cvd`). Tập tin chữ ký mà hoạt động trực tiếp với nội dung tập tin sử dụng phần mở rộng này.
- __fdb__: Tập tin chữ ký tên tập tin (cơ sở dữ liệu chữ ký ClamAV trước đây được hỗ trợ các chữ ký tên tập tin, nhưng không nữa; SigTool không tạo ra bất kỳ tập tin chữ ký sử dụng phần mở rộng này; duy trì bởi vì tính hữu dụng của phpMussel). Tập tin chữ ký mà hoạt động với các tên tập tin sử dụng phần mở rộng này.
- __hdb__: Tập tin chữ ký băm (chúng được trích xuất từ các tập tin chữ ký `.hdb` chứa trong `daily.cvd` và `main.cvd`). Tập tin chữ ký mà hoạt động với băm tập tin sử dụng phần mở rộng này.
- __htdb__: Tập tin chữ ký HTML (chúng được trích xuất từ các tập tin chữ ký `.ndb` chứa trong `daily.cvd` và `main.cvd`). Tập tin chữ ký mà hoạt động với nội dung được chuẩn hoá HTML sử dụng phần mở rộng này.
- __mdb__: Tập tin chữ ký phần PE (chúng được trích xuất từ các tập tin chữ ký `.mdb` chứa trong `daily.cvd` và `main.cvd`). Tập tin chữ ký mà hoạt động với siêu dữ liệu phần PE sử dụng phần mở rộng này.
- __medb__: Tập tin chữ ký kéo dài PE (đây là một định dạng được tạo ra cho phpMussel, và không liên quan gì đến cơ sở dữ liệu chữ ký ClamAV; SigTool không tạo ra bất kỳ tập tin chữ ký sử dụng phần mở rộng này; chúng được viết bằng tay cho repository `phpMussel/Signatures`). Tập tin chữ ký mà hoạt động với siêu dữ liệu PE (khác với siêu dữ liệu phần PE) sử dụng phần mở rộng này.
- __ndb__: Tập tin chữ ký chuẩn hoá (chúng được trích xuất từ các tập tin chữ ký `.ndb` chứa trong `daily.cvd` và `main.cvd`). Tập tin chữ ký mà hoạt động với nội dung được chuẩn hoá ANSI sử dụng phần mở rộng này.
- __udb__: Tập tin chữ ký URL (đây là một định dạng được tạo ra cho phpMussel, và không liên quan gì đến cơ sở dữ liệu chữ ký ClamAV; SigTool *hiện* không tạo bất kỳ tập tin chữ ký nào bằng cách sử dụng phần mở rộng này, mặc dù điều này có thể thay đổi trong tương lai; hiện tại, chúng được viết bằng tay cho repository `phpMussel/Signatures`). Tập tin chữ ký mà hoạt động với URL sử dụng phần mở rộng này.
- __ldb__: Tập tin chữ ký lôgic (tại một số điểm trong tương lai, cho một phiên bản SigTool trong tương lai, chúng sẽ được trích xuất từ các tập tin chữ ký `.ldb` chứa trong `daily.cvd` và `main.cvd`, nhưng chưa được hỗ trợ bởi SigTool hay phpMussel). Tập tin chữ ký mà hoạt động với các quy tắc lôgic khác nhau sử dụng phần mở rộng này.


---


Lần cuối cập nhật: 2021.07.22.
