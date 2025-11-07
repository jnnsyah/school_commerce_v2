<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="grade_id" class="form-label">Tingkatan Kelas *</label>
            <select class="form-select @error('grade_id') is-invalid @enderror" 
                    id="grade_id" name="grade_id" required>
                <option value="">Pilih Tingkatan</option>
                @foreach($grades as $grade)
                <option value="{{ $grade->id }}" 
                        {{ old('grade_id', $class->grade_id ?? '') == $grade->id ? 'selected' : '' }}>
                    {{ $grade->name }}
                </option>
                @endforeach
            </select>
            @error('grade_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="major_id" class="form-label">Jurusan *</label>
            <select class="form-select @error('major_id') is-invalid @enderror" 
                    id="major_id" name="major_id" required>
                <option value="">Pilih Jurusan</option>
                @foreach($majors as $major)
                <option value="{{ $major->id }}" 
                        {{ old('major_id', $class->major_id ?? '') == $major->id ? 'selected' : '' }}>
                    {{ $major->name }} ({{ $major->short_name }})
                </option>
                @endforeach
            </select>
            @error('major_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="section_id" class="form-label">Section *</label>
            <select class="form-select @error('section_id') is-invalid @enderror" 
                    id="section_id" name="section_id" required>
                <option value="">Pilih Section</option>
                @foreach($sections as $section)
                <option value="{{ $section->id }}" 
                        {{ old('section_id', $class->section_id ?? '') == $section->id ? 'selected' : '' }}>
                    {{ $section->name }}
                </option>
                @endforeach
            </select>
            @error('section_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="mb-3">
            <label for="teacher_id" class="form-label">Wali Kelas *</label>
            <select class="form-select @error('teacher_id') is-invalid @enderror" 
                    id="teacher_id" name="teacher_id" required>
                <option value="">Pilih Wali Kelas</option>
                @foreach($teachers as $teacher)
                <option value="{{ $teacher->id }}" 
                        {{ old('teacher_id', $class->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                    {{ $teacher->name }}
                </option>
                @endforeach
            </select>
            @error('teacher_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" 
               id="is_active" name="is_active" value="1"
               {{ old('is_active', $class->is_active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
            Kelas Aktif
        </label>
    </div>
</div>